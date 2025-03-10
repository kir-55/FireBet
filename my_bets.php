<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet - My Bets</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="main-container">
        <?php
        // Include the database credentials
        include 'config/variables.php';

        // Start the session
        if (session_status() == PHP_SESSION_NONE) {
            session_start(); // Ensure the session is started only if it hasn't been started already
        }
        

        // Check if the user is logged in
        if (!isset($_SESSION['student_id'])) {
            header("Location: login.php");
            exit();
        }

        // Connect to the database
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Get the student ID from the session
        $student_id = $_SESSION['student_id'];

        // Query to get the user's bets
        $sql = "SELECT bets.amount, bets.profit_loss, bets.status, `groups`.id AS group_id, `groups`.leader_id, students.name AS leader_name, vbanks.title AS vbank_title
                FROM bets
                JOIN `groups` ON bets.group_id = `groups`.id
                JOIN students ON `groups`.leader_id = students.id
                JOIN vbanks ON `groups`.vbank_id = vbanks.id
                WHERE bets.student_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $bets_result = $stmt->get_result();

        if ($bets_result->num_rows > 0) {
            echo "<div class='panel'>";
            echo "<h2>My Bets</h2>";
            while ($bet = $bets_result->fetch_assoc()) {
                // Calculate the updated coefficient
                $sql = "SELECT SUM(amount) AS group_bets FROM bets WHERE group_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $bet['group_id']);
                $stmt->execute();
                $group_bets_result = $stmt->get_result();
                $group_bets = $group_bets_result->fetch_assoc()["group_bets"];
                $group_bets = $group_bets > 0 ? $group_bets : 0.01; // Avoid division by zero
                $probability = (isset($total_bets) && $total_bets > 0) ? $group_bets / $total_bets : 0;
                $updated_coefficient = $probability > 0 ? round(1 / $probability, 2) . 'x' : '0x';

                echo "<div class='bet'>";
                echo "<p><strong>V-Bank:</strong> " . $bet['vbank_title'] . "</p>";
                echo "<p><strong>Group:</strong> Group " . $bet['group_id'] . "</p>";
                echo "<p><strong>Leader:</strong> " . $bet['leader_name'] . "</p>";
                echo "<p><strong>Amount:</strong> " . $bet['amount'] . " zł</p>";
                echo "<p><strong>Profit/Loss:</strong> " . ($bet['profit_loss'] !== null ? $bet['profit_loss'] . " zł" : "-") . "</p>";
                echo "<p><strong>Updated Coefficient:</strong> " . $updated_coefficient . "</p>";
                echo "<p><strong>Status:</strong> " . $bet['status'] . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>You have no bets placed.</p>";
        }

        // Close the connection
        $conn->close();
        ?>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>

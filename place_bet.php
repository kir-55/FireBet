<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet - Place Bet</title>
    <link rel="stylesheet" href="styles.css">
    <?php
    
    include 'config/variables.php';

    
    session_start();

    
    if (!isset($_SESSION['student_id'])) {
        header("Location: login.php");
        exit();
    }

    
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $group_id = $_GET['group_id'];

    
    $sql = "SELECT `groups`.id, `groups`.leader_id, students.name AS leader_name, vbanks.title AS vbank_title, vbanks.id AS vbank_id
            FROM `groups`
            JOIN students ON `groups`.leader_id = students.id
            JOIN vbanks ON `groups`.vbank_id = vbanks.id
            WHERE `groups`.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $group_result = $stmt->get_result();
    $group = $group_result->fetch_assoc();

    
    $sql = "SELECT SUM(bets.amount) AS total_bets FROM bets JOIN `groups` ON bets.group_id = `groups`.id WHERE `groups`.vbank_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group['vbank_id']);
    $stmt->execute();
    $total_bets_result = $stmt->get_result();
    $total_bets = $total_bets_result->fetch_assoc()["total_bets"];
    $total_bets = $total_bets ? $total_bets : 0; 

    
    $sql = "SELECT SUM(amount) AS group_bets FROM bets WHERE group_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $group_id);
    $stmt->execute();
    $group_bets_result = $stmt->get_result();
    $group_bets = $group_bets_result->fetch_assoc()["group_bets"];
    $group_bets = $group_bets > 0 ? $group_bets : 0.01; 
    $probability = $total_bets > 0 ? $group_bets / $total_bets : 0;
    $current_coefficient = $probability > 0 ? round(1 / $probability, 2) . 'x' : '0x';
    ?>
    <script>
        const totalBets = <?php echo $total_bets; ?>;
        const groupBets = <?php echo $group_bets; ?>;
    </script>
    <script src="script.js" defer></script>
</head>
<body>
    <?php include 'config/constants.php'; ?>
    <?php include 'header.php'; ?>
    <main class="main-container">
        <?php
        echo "<div class='bet-container'>";
        echo "<h2>Place Bet on Group of " . $group['leader_name'] . "</h2>";
        echo "<p>Current Coefficient: <span id='current-coefficient'>" . $current_coefficient . "</span></p>";
        echo "<form id='bet-form' action='place_bet_process.php' method='post'>";
        echo "<input type='hidden' name='group_id' value='" . $group_id . "'>";
        echo "<label for='amount'>Amount:</label>";
        echo "<input type='number' id='amount' name='amount' required>";
        echo "<p>New Coefficient: <span id='new-coefficient'>-</span></p>";
        echo "<button type='submit'>Place Bet</button>";
        echo "</form>";
        echo "</div>";

        
        $conn->close();
        ?>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>

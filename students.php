<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet - Students</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            if (window.location.hash) {
                const studentId = window.location.hash.substring(2);
                const studentElement = document.getElementById("s" + studentId);
                if (studentElement) {
                    studentElement.scrollIntoView({ behavior: "smooth" });
                    studentElement.style.backgroundColor = "#444444"; // Highlight the student
                }
            }
        });
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <main>
        <div class="students-container">
            <?php
            // Include the database credentials
            include 'config/variables.php';

            // Connect to the database
            $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Query to get all students
            $sql = "SELECT id, name, evaluation, description FROM students";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each student
                while($row = $result->fetch_assoc()) {
                    echo "<div id='s" . $row["id"] . "' class='student-info'>";
                    echo "<h2>" . $row["name"] . "<span class='student-score'>" . $row["evaluation"] . "</span></h2>";
                    echo "<p>" . $row["description"] . "</p>";
                    echo "</div>";
                }
            } else {
                echo "<p>No students found.</p>";
            }

            // Close the connection
            $conn->close();
            ?>
        </div>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database credentials
include 'config/variables.php';

// Check if the user is logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$group_id = $_POST['group_id'];
$amount = $_POST['amount'];
$student_id = $_SESSION['student_id']; // Assuming student_id is stored in session

// Debugging: Check if the student_id exists in the students table
$sql = "SELECT id FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    error_log("Error: Student ID " . $student_id . " does not exist in the students table.");
    die("Error: Student ID does not exist.");
}

// Prepare the SQL statement
$sql = "INSERT INTO bets (group_id, student_id, amount) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iid", $group_id, $student_id, $amount);

// Execute the statement
if ($stmt->execute() === TRUE) {
    echo "Bet placed successfully";
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

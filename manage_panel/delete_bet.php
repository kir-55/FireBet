<?php
// Include the database credentials
include '../config/variables.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the bet ID from the request
$bet_id = $_POST['bet_id'];

// Prepare and execute the SQL statement to delete the bet
$sql = "DELETE FROM bets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bet_id);
if ($stmt->execute()) {
    echo "Bet deleted successfully";
} else {
    echo "Failed to delete bet";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

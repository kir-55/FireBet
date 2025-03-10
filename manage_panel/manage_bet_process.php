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

// Get the bet ID and new status from the form
$bet_id = $_POST['bet_id'];
$status = $_POST['status'];

// Validate the status
$valid_statuses = ['in_progress', 'payed', 'denied', 'cashed'];
if (!in_array($status, $valid_statuses)) {
    die("Invalid status");
}

// Update the status of the bet
$sql = "UPDATE bets SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $bet_id);
$stmt->execute();

// Redirect to the manage panel
header("Location: index.php");
exit();

// Close the connection
$conn->close();
?>

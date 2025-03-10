<?php
session_start();
include 'config/variables.php';

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

// Get the comment data from the form
$author_id = $_SESSION['student_id'];
$vbank_id = $_POST['vbank_id'];
$content = $_POST['content'];
$date = date('Y-m-d');

// Debugging: Check if the author_id exists in the students table
$sql = "SELECT id FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    error_log("Error: Author ID " . $author_id . " does not exist in the students table.");
    die("Error: Author ID does not exist.");
}

// Insert the comment into the database
$sql = "INSERT INTO comments (author_id, vbank_id, content, date) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iiss", $author_id, $vbank_id, $content, $date);
$stmt->execute();

// Redirect back to the index page
header("Location: index.php");
exit();

// Close the connection
$conn->close();
?>

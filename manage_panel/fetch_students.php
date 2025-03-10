<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database credentials
include '../config/constants.php';
include '../config/variables.php';

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the V-Bank ID and Group ID from the request
$vbank_id = $_GET['vbank_id'];
$group_id = $_GET['group_id'];

// Prepare the SQL statement
$sql = "SELECT id, name FROM students WHERE id NOT IN (SELECT student_id FROM student_group WHERE group_id IN (SELECT id FROM `groups` WHERE vbank_id = ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}

// Return the students as JSON
echo json_encode($students);

// Close the statement and connection
$stmt->close();
$conn->close();
?>

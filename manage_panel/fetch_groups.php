<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include the database credentials
include '../config/variables.php';

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the V-Bank ID from the request
$vbank_id = $_GET['vbank_id'];

// Prepare the SQL statement
$sql = "SELECT `groups`.id, students.name as leader FROM `groups` LEFT JOIN students ON students.id = leader_id WHERE vbank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$result = $stmt->get_result();

$groups = [];
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}

// Return the groups as JSON
echo json_encode($groups);

// Close the connection
$stmt->close();
$conn->close();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include '../config/constants.php';
include '../config/variables.php';


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_id = $_GET['vbank_id'];


$sql = "SELECT id, name FROM students WHERE id NOT IN (SELECT leader_id FROM `groups` WHERE vbank_id = ?) AND id NOT IN (SELECT student_id FROM student_group WHERE group_id IN (SELECT id FROM `groups` WHERE vbank_id = ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $vbank_id, $vbank_id);
$stmt->execute();
$result = $stmt->get_result();

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = $row;
}


echo json_encode($students);


$stmt->close();
$conn->close();
?>

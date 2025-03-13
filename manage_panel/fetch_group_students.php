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


$group_id = $_GET['group_id'];


$sql = "SELECT students.id, students.name FROM students JOIN student_group ON students.id = student_group.student_id WHERE student_group.group_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $group_id);
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

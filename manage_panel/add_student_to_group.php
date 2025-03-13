<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['name']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager')) {
    header("Location: ../login.php");
    exit();
}


include '../config/constants.php';
include '../config/variables.php';


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_id = $_POST['vbank_id'];
$group_id = $_POST['group_id'];
$student_id = $_POST['student_id'];


$sql = "INSERT INTO student_group (group_id, student_id) VALUES ('$group_id', '$student_id')";
if ($conn->query($sql) === TRUE) {
    echo "Student added to group successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();


header("Location: ../index.php");
?>

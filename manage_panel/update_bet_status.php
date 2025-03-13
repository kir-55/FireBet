<?php

include '../config/variables.php';


session_start();


if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$bet_id = $_POST['bet_id'];
$status = $_POST['status'];


$valid_statuses = ['in_process', 'payed', 'denied', 'cashed'];
if (!in_array($status, $valid_statuses)) {
    die("Invalid status");
}


$sql = "UPDATE bets SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $bet_id);
$stmt->execute();


$conn->close();
?>

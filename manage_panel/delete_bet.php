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


$sql = "DELETE FROM bets WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bet_id);
if ($stmt->execute()) {
    echo "Bet deleted successfully";
} else {
    echo "Failed to delete bet";
}


$stmt->close();
$conn->close();
?>

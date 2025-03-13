<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include 'config/variables.php';


if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$group_id = $_POST['group_id'];
$amount = $_POST['amount'];
$student_id = $_SESSION['student_id']; 


$sql = "SELECT id FROM students WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    error_log("Error: Student ID " . $student_id . " does not exist in the students table.");
    die("Error: Student ID does not exist.");
}


$sql = "INSERT INTO bets (group_id, student_id, amount) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iid", $group_id, $student_id, $amount);


if ($stmt->execute() === TRUE) {
    echo "Bet placed successfully";
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();
?>

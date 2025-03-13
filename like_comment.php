<?php
session_start();
include 'config/variables.php';


if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$studentId = $_SESSION['student_id'];


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['commentId'];


$sql = "SELECT COUNT(*) AS amount FROM likes WHERE comment_id = ? AND author_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $commentId, $studentId);
$stmt->execute();
$result = $stmt->get_result();
$likesFromStudent = $result->fetch_assoc()["amount"];

if ($likesFromStudent > 0) {
    
    $sql = "DELETE FROM likes WHERE comment_id = ? AND author_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $commentId, $studentId);
    $stmt->execute();
} else {
    
    $sql = "INSERT INTO likes (comment_id, author_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $commentId, $studentId);
    $stmt->execute();
}


$sql = "SELECT COUNT(*) AS amount FROM likes WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $commentId);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

$response = array('success' => true, 'likes' => $comment['amount']);
echo json_encode($response);


$conn->close();
?>

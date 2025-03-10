<?php
session_start();
include 'config/variables.php';

// Check if the user is logged in
if (!isset($_SESSION['student_id'])) {
    header("Location: login.php");
    exit();
}

$studentId = $_SESSION['student_id'];

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the comment ID from the request
$data = json_decode(file_get_contents('php://input'), true);
$commentId = $data['commentId'];

// Check if the student has already liked the comment
$sql = "SELECT COUNT(*) AS amount FROM likes WHERE comment_id = ? AND author_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $commentId, $studentId);
$stmt->execute();
$result = $stmt->get_result();
$likesFromStudent = $result->fetch_assoc()["amount"];

if ($likesFromStudent > 0) {
    // If the student has already liked the comment, remove the like
    $sql = "DELETE FROM likes WHERE comment_id = ? AND author_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $commentId, $studentId);
    $stmt->execute();
} else {
    // If the student has not liked the comment, add a like
    $sql = "INSERT INTO likes (comment_id, author_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $commentId, $studentId);
    $stmt->execute();
}

// Get the updated likes count
$sql = "SELECT COUNT(*) AS amount FROM likes WHERE comment_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $commentId);
$stmt->execute();
$result = $stmt->get_result();
$comment = $result->fetch_assoc();

$response = array('success' => true, 'likes' => $comment['amount']);
echo json_encode($response);

// Close the connection
$conn->close();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['name']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}


include '../config/constants.php';
include '../config/variables.php';


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $_POST['name'];
$role = $_POST['role'];
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); 
$evaluation = $_POST['evaluation'];
$description = $_POST['description'];


$sql = "INSERT INTO students (name, role, email, password, evaluation, description) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssds", $name, $role, $email, $password, $evaluation, $description);


if ($stmt->execute() === TRUE) {
    echo "New person added successfully";
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();


header("Location: ../index.php");
?>

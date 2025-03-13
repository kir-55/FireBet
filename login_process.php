<?php

include 'config/variables.php';


session_start();


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$name = $_POST['name'];
$password = $_POST['password'];


$sql = "SELECT * FROM students WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['student_id'] = $user['id']; 
        
        header("Location: index.php");
        exit();
    } else {
        
        header("Location: login_error.php");
        exit();
    }
} else {
    
    header("Location: login_error.php");
    exit();
}


$conn->close();
?>

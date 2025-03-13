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


$name = 'Admin';
$role = 'admin';
$password = password_hash('adminpassword', PASSWORD_DEFAULT); 
$evaluation = 70;
$description = 'Średni';


$sql = 'SELECT * FROM students WHERE role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    
    $sql = "INSERT INTO students (name, role, password, evaluation, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", $name, $role, $password, $evaluation, $description);

    if ($stmt->execute() === TRUE) {
        
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;
        $_SESSION['student_id'] = $stmt->insert_id; 

        
        error_log("Admin user created with student_id: " . $_SESSION['student_id']);
    }
}


$stmt->close();
$conn->close();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['name']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Include the database credentials
include '../config/constants.php';
include '../config/variables.php';

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the form data
$name = $_POST['name'];
$role = $_POST['role'];
$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
$evaluation = $_POST['evaluation'];
$description = $_POST['description'];

// Prepare the SQL statement
$sql = "INSERT INTO students (name, role, email, password, evaluation, description) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssds", $name, $role, $email, $password, $evaluation, $description);

// Execute the statement
if ($stmt->execute() === TRUE) {
    echo "New person added successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect back to the manage panel
header("Location: ../index.php");
?>

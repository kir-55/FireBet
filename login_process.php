<?php
// Include the database credentials
include 'config/variables.php';

// Start the session
session_start();

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the name and password from the form
$name = $_POST['name'];
$password = $_POST['password'];

// Query to check if the user exists
$sql = "SELECT * FROM students WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // User exists, fetch user data
    $user = $result->fetch_assoc();
    // Verify the password
    if (password_verify($password, $user['password'])) {
        // Store user data in session
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['student_id'] = $user['id']; // Store student_id in session
        // Redirect to the homepage
        header("Location: index.php");
        exit();
    } else {
        // Invalid password, redirect to the error page
        header("Location: login_error.php");
        exit();
    }
} else {
    // User does not exist, redirect to the error page
    header("Location: login_error.php");
    exit();
}

// Close the connection
$conn->close();
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and has the appropriate role
if (!isset($_SESSION['name']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager')) {
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
$vbank_id = $_POST['vbank_id'];
$group_id = $_POST['group_id'];
$student_id = $_POST['student_id'];

// Insert the student into the group
$sql = "INSERT INTO student_group (group_id, student_id) VALUES ('$group_id', '$student_id')";
if ($conn->query($sql) === TRUE) {
    echo "Student added to group successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close the connection
$conn->close();

// Redirect back to the manage panel
header("Location: ../index.php");
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Ensure the session is started only if it hasn't been started already
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

// Admin student details
$name = 'Admin';
$role = 'admin';
$password = password_hash('adminpassword', PASSWORD_DEFAULT); // Hash the password
$evaluation = 70;
$description = 'Średni';

// Check if the admin user already exists
$sql = 'SELECT * FROM students WHERE role = "admin"';
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // Insert the admin student into the database
    $sql = "INSERT INTO students (name, role, password, evaluation, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssds", $name, $role, $password, $evaluation, $description);

    if ($stmt->execute() === TRUE) {
        // Automatically log in the user as admin
        $_SESSION['name'] = $name;
        $_SESSION['role'] = $role;
        $_SESSION['student_id'] = $stmt->insert_id; // Correctly set the student_id in the session

        // Debugging: Check if the student_id is correctly set
        error_log("Admin user created with student_id: " . $_SESSION['student_id']);
    }
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>

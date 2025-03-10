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
$leader_id = $_POST['leader_id'];
$grade = isset($_POST['grade']) && $_POST['grade'] !== '' ? $_POST['grade'] : NULL;

// Prepare the SQL statement to insert the new group
$sql = "INSERT INTO `groups` (vbank_id, leader_id, grade) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $vbank_id, $leader_id, $grade);

// Execute the statement
if ($stmt->execute() === TRUE) {
    // Get the ID of the newly inserted group
    $group_id = $stmt->insert_id;

    // Add the leader as a member of the group
    $sql = "INSERT INTO student_group (group_id, student_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $group_id, $leader_id);
    $stmt->execute();

    echo "New group added successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Redirect back to the manage panel
header("Location: ../index.php");
?>

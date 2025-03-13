<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['name']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager')) {
    header("Location: ../login.php");
    exit();
}


include '../config/constants.php';
include '../config/variables.php';


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_id = $_POST['vbank_id'];
$leader_id = $_POST['leader_id'];
$grade = isset($_POST['grade']) && $_POST['grade'] !== '' ? $_POST['grade'] : NULL;


$sql = "INSERT INTO `groups` (vbank_id, leader_id, grade) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $vbank_id, $leader_id, $grade);


if ($stmt->execute() === TRUE) {
    
    $group_id = $stmt->insert_id;

    
    $sql = "INSERT INTO student_group (group_id, student_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $group_id, $leader_id);
    $stmt->execute();

    echo "New group added successfully";
} else {
    echo "Error: " . $stmt->error;
}


$stmt->close();
$conn->close();


header("Location: ../index.php");
?>

<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include '../config/variables.php';


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_id = $_GET['vbank_id'];


$sql = "SELECT `groups`.id, students.name as leader FROM `groups` LEFT JOIN students ON students.id = leader_id WHERE vbank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$result = $stmt->get_result();

$groups = [];
while ($row = $result->fetch_assoc()) {
    $groups[] = $row;
}


echo json_encode($groups);


$stmt->close();
$conn->close();
?>

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


$title = $_POST['title'];
$date = $_POST['date'];


$sql = "INSERT INTO vbanks (title, date) VALUES ('$title', '$date')";
if ($conn->query($sql) === TRUE) {
    echo "New v-bank added successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();


header("Location: ../index.php");
?>

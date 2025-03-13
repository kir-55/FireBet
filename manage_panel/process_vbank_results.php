<?php

include '../config/variables.php';


session_start();


if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_id = $_POST['vbank_id'];
$grades = $_POST['grades'];


foreach ($grades as $group_id => $grade) {
    $sql = "UPDATE `groups` SET grade = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $grade, $group_id);
    $stmt->execute();
}


$sql = "SELECT id, grade FROM `groups` WHERE vbank_id = ? ORDER BY grade DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$result = $stmt->get_result();

$highest_grade_groups = [];
$highest_grade = null;
while ($row = $result->fetch_assoc()) {
    if ($highest_grade === null) {
        $highest_grade = $row['grade'];
    }
    if ($row['grade'] == $highest_grade) {
        $highest_grade_groups[] = $row['id'];
    } else {
        break;
    }
}


$sql = "SELECT SUM(amount) AS total_bets FROM bets WHERE group_id IN (SELECT id FROM `groups` WHERE vbank_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$total_bets_result = $stmt->get_result();
$total_bets = $total_bets_result->fetch_assoc()["total_bets"];
$total_bets = $total_bets ? $total_bets : 0; 


$sql = "SELECT SUM(amount) AS total_bets FROM bets WHERE group_id IN (" . implode(',', $highest_grade_groups) . ")";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_winers_result = $stmt->get_result();
$total_winers = $total_winers_result->fetch_assoc()["total_bets"];
$total_winers = $total_winers ? $total_winers : 0; 


$sql = "SELECT bets.id, bets.amount, bets.group_id, bets.student_id, bets.profit_loss, `groups`.grade
        FROM bets
        JOIN `groups` ON bets.group_id = `groups`.id
        WHERE `groups`.vbank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$bets_result = $stmt->get_result();

while ($bet = $bets_result->fetch_assoc()) {
    if (in_array($bet['group_id'], $highest_grade_groups)) {
        
        $sql = "SELECT SUM(amount) AS group_bets FROM bets WHERE group_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bet['group_id']);
        $stmt->execute();
        $group_bets_result = $stmt->get_result();
        $group_bets = $group_bets_result->fetch_assoc()["group_bets"];
        $group_bets = $group_bets > 0 ? $group_bets : 0.01; 

        
        $coefficient = $total_bets / $total_winers;
        $profit_loss = round($bet['amount'] * ($coefficient - 1), 2);

        $sql = "UPDATE bets SET profit_loss = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $profit_loss, $bet['id']);
        $stmt->execute();
    } else {
        
        $profit_loss = round(-$bet['amount'], 2);
        $sql = "UPDATE bets SET profit_loss = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $profit_loss, $bet['id']);
        $stmt->execute();
    }
}


$sql = "SELECT id FROM students";
$students_result = $conn->query($sql);

while ($student = $students_result->fetch_assoc()) {
    $student_id = $student['id'];

    
    $sql = "SELECT AVG(`groups`.grade) AS avg_grade
            FROM `groups`
            JOIN student_group ON `groups`.id = student_group.group_id
            WHERE student_group.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $avg_grade_result = $stmt->get_result();
    $avg_grade = $avg_grade_result->fetch_assoc()["avg_grade"];

    
    $evaluation = $avg_grade == 1 ? 0 : round(($avg_grade / 6) * 100, 2);

    
    $sql = "UPDATE students SET evaluation = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $evaluation, $student_id);
    $stmt->execute();
}


header("Location: index.php?message=V-Bank results processed successfully");


$conn->close();
?>
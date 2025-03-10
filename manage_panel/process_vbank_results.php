<?php
// Include the database credentials
include '../config/variables.php';

// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['name']) || !isset($_SESSION['student_id'])) {
    header("Location: ../login.php");
    exit();
}

// Connect to the database
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the V-Bank ID and grades from the form
$vbank_id = $_POST['vbank_id'];
$grades = $_POST['grades'];

// Update the grades for each group
foreach ($grades as $group_id => $grade) {
    $sql = "UPDATE `groups` SET grade = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $grade, $group_id);
    $stmt->execute();
}

// Find the groups with the highest grade
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

// Calculate the total bets for the winning groups
$sql = "SELECT SUM(amount) AS total_bets FROM bets WHERE group_id IN (SELECT id FROM `groups` WHERE vbank_id = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$total_bets_result = $stmt->get_result();
$total_bets = $total_bets_result->fetch_assoc()["total_bets"];
$total_bets = $total_bets ? $total_bets : 0; // Set to zero if no bets

// Calculate the total bets for the winning groups
$sql = "SELECT SUM(amount) AS total_bets FROM bets WHERE group_id IN (" . implode(',', $highest_grade_groups) . ")";
$stmt = $conn->prepare($sql);
$stmt->execute();
$total_winers_result = $stmt->get_result();
$total_winers = $total_winers_result->fetch_assoc()["total_bets"];
$total_winers = $total_winers ? $total_winers : 0; // Set to zero if no bets

// Process the bets for the V-Bank
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
        // Calculate the profit/loss for winning bets
        $sql = "SELECT SUM(amount) AS group_bets FROM bets WHERE group_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bet['group_id']);
        $stmt->execute();
        $group_bets_result = $stmt->get_result();
        $group_bets = $group_bets_result->fetch_assoc()["group_bets"];
        $group_bets = $group_bets > 0 ? $group_bets : 0.01; // Avoid division by zero

        // Calculate the player's share of the winnings
        $coefficient = $total_bets / $total_winers;
        $profit_loss = round($bet['amount'] * ($coefficient - 1), 2);

        $sql = "UPDATE bets SET profit_loss = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $profit_loss, $bet['id']);
        $stmt->execute();
    } else {
        // Set profit/loss to negative amount for losing bets
        $profit_loss = round(-$bet['amount'], 2);
        $sql = "UPDATE bets SET profit_loss = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di", $profit_loss, $bet['id']);
        $stmt->execute();
    }
}

// Recalculate the evaluation for each student
$sql = "SELECT id FROM students";
$students_result = $conn->query($sql);

while ($student = $students_result->fetch_assoc()) {
    $student_id = $student['id'];

    // Calculate the average grade for the student across all V-Banks
    $sql = "SELECT AVG(`groups`.grade) AS avg_grade
            FROM `groups`
            JOIN student_group ON `groups`.id = student_group.group_id
            WHERE student_group.student_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $avg_grade_result = $stmt->get_result();
    $avg_grade = $avg_grade_result->fetch_assoc()["avg_grade"];

    // Transform the average grade into a 100-point system
    $evaluation = $avg_grade == 1 ? 0 : round(($avg_grade / 6) * 100, 2);

    // Update the student's evaluation
    $sql = "UPDATE students SET evaluation = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("di", $evaluation, $student_id);
    $stmt->execute();
}

// Redirect to the manage panel with a success message
header("Location: index.php?message=V-Bank results processed successfully");

// Close the connection
$conn->close();
?>
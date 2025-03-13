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


$sql = "SELECT id, title, date FROM vbanks WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$vbank_result = $stmt->get_result();
$vbank = $vbank_result->fetch_assoc();


$sql = "SELECT `groups`.id, `groups`.leader_id, students.name AS leader_name, `groups`.grade
        FROM `groups`
        JOIN students ON `groups`.leader_id = students.id
        WHERE `groups`.vbank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$groups_result = $stmt->get_result();

$groups = [];
while ($group = $groups_result->fetch_assoc()) {
    $groups[] = $group;
}


$sql = "SELECT bets.id, bets.amount, bets.profit_loss, bets.status, students.name AS student_name, `groups`.id AS group_id, `groups`.leader_id, students.name AS leader_name
        FROM bets
        JOIN `groups` ON bets.group_id = `groups`.id
        JOIN students ON bets.student_id = students.id
        WHERE `groups`.vbank_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $vbank_id);
$stmt->execute();
$bets_result = $stmt->get_result();

$bets = [];
while ($bet = $bets_result->fetch_assoc()) {
    $bets[] = $bet;
}


$html = "<div class='panel'>";
$html .= "<h2>Manage V-Bank: " . $vbank["title"] . "  " . $vbank["date"] . "</h2>";

if (count($groups) > 0) {
    $html .= "<form action='process_vbank_results.php' method='post'>";
    $html .= "<input type='hidden' name='vbank_id' value='" . $vbank_id . "'>";
    $html .= "<table>";
    $html .= "<tr><th>Group</th><th>Leader</th><th>Grade</th></tr>";
    foreach ($groups as $group) {
        $html .= "<tr>";
        $html .= "<td>Group " . $group['id'] . "</td>";
        $html .= "<td>" . $group['leader_name'] . "</td>";
        $html .= "<td>";
        $html .= "<select name='grades[" . $group['id'] . "]'>";
        for ($i = 1; $i <= 6; $i++) {
            $html .= "<option value='" . $i . "'" . ($group['grade'] == $i ? ' selected' : '') . ">" . $i . "</option>";
        }
        $html .= "</select>";
        $html .= "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
    $html .= "<button type='submit'>Apply Grades</button>";
    $html .= "</form>";
} else {
    $html .= "<p>No groups found for this V-Bank.</p>";
}

if (count($bets) > 0) {
    $html .= "<table>";
    $html .= "<tr><th>Student</th><th>Group</th><th>Amount</th><th>Profit/Loss</th><th>Status</th><th>Actions</th></tr>";
    foreach ($bets as $bet) {
        $html .= "<tr>";
        $html .= "<td>" . $bet['student_name'] . "</td>";
        $html .= "<td>Group " . $bet['group_id'] . "</td>";
        $html .= "<td>" . $bet['amount'] . " zł</td>";
        $html .= "<td>" . ($bet['profit_loss'] !== null ? $bet['profit_loss'] . " zł" : "-") . "</td>";
        $html .= "<td>";
        $html .= "<select name='status' onchange='updateBetStatus(" . $bet['id'] . ", this.value)'>";
        $html .= "<option value='in_process'" . ($bet['status'] == 'in_process' ? ' selected' : '') . ">In Process</option>";
        $html .= "<option value='payed'" . ($bet['status'] == 'payed' ? ' selected' : '') . ">Payed</option>";
        $html .= "<option value='denied'" . ($bet['status'] == 'denied' ? ' selected' : '') . ">Denied</option>";
        $html .= "<option value='cashed'" . ($bet['status'] == 'cashed' ? ' selected' : '') . ">Cashed</option>";
        $html .= "</select>";
        $html .= "</td>";
        $html .= "<td>";
        $html .= "<button type='button' class='button-no-border' onclick='deleteBet(" . $bet['id'] . ")'>Delete</button>";
        $html .= "</td>";
        $html .= "</tr>";
    }
    $html .= "</table>";
} else {
    $html .= "<p>No bets found for this V-Bank.</p>";
}

$html .= "</div>";


echo json_encode(['html' => $html]);


$conn->close();
?>

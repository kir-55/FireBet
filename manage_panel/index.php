<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


include '../config/constants.php';
include '../config/variables.php';


include 'add_admin.php';


if (!isset($_SESSION['name']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'manager')) {
    header("Location: ../login.php");
    exit();
}


$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$vbank_sql = "SELECT id, title FROM vbanks";
$vbank_result = $conn->query($vbank_sql);
$vbanks = [];
while ($vbank = $vbank_result->fetch_assoc()) {
    $vbanks[] = $vbank;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet - Manage Panel</title>
    <link rel="stylesheet" href="../styles.css">
    <script src="../script.js" defer></script>
    <script>
        function fetchGroups(vbankId) {
            fetch('fetch_groups.php?vbank_id=' + vbankId)
                .then(response => response.json())
                .then(data => {
                    const groupSelect = document.getElementById('group_id');
                    groupSelect.innerHTML = '<option value="">Select Group</option>';
                    data.forEach(group => {
                        groupSelect.innerHTML += `<option value="${group.id}">Group of ${group.leader}</option>`;
                    });
                });
        }

        function fetchStudents(vbankId, groupId) {
            console.log("v = " + vbankId );
            console.log("g = " + groupId );
            fetch('fetch_students.php?vbank_id=' + vbankId + '&group_id=' + groupId)
                .then(response => response.json())
                .then(data => {
                    const studentSelect = document.getElementById('student_id');
                    studentSelect.innerHTML = '<option value="">Select Student</option>';
                    data.forEach(student => {
                        studentSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
                    });
                });

            fetch('fetch_group_students.php?group_id=' + groupId)
                .then(response => response.json())
                .then(data => {
                    const groupStudentsTable = document.getElementById('group_students_table');

                    groupStudentsTable.innerHTML = '<tr><th>Student ID</th><th>Student Name</th></tr>';
                    data.forEach(student => {
                        groupStudentsTable.innerHTML += `<tr><td>${student.id}</td><td>${student.name}</td></tr>`;
                    });
                    groupStudentsTable.style.display = "block";
                });
        }

        function fetchAvailableLeaders(vbankId) {
            fetch('fetch_available_leaders.php?vbank_id=' + vbankId)
                .then(response => response.json())
                .then(data => {
                    const leaderSelect = document.getElementById('leader_id');
                    leaderSelect.innerHTML = '<option value="">Select Leader</option>';
                    data.forEach(student => {
                        leaderSelect.innerHTML += `<option value="${student.id}">${student.name}</option>`;
                    });
                });
        }

        function updateBetStatus(betId, status) {
            fetch('update_bet_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `bet_id=${betId}&status=${status}`
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            });
        }

        function deleteBet(betId) {
            if (confirm("Are you sure you want to delete this bet?")) {
                fetch('delete_bet.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `bet_id=${betId}`
                })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    location.reload(); 
                });
            }
        }

        function processVBankResults(vbankId) {
            fetch('process_vbank_results.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `vbank_id=${vbankId}`
            })
            .then(response => response.text())
            .then(data => {
                console.log(data);
                location.reload(); 
            });
        }

        function fetchVBankDetails(vbankId) {
            fetch('fetch_vbank_details.php?vbank_id=' + vbankId)
                .then(response => response.json())
                .then(data => {
                    const vbankDetailsContainer = document.getElementById('vbank-details-container');
                    vbankDetailsContainer.innerHTML = data.html;
                });
        }
    </script>
</head>
<body>
    <?php include '../header.php'; ?>
    <main>
        <div class="manage-panel-container">
            <h2>Manage Panel</h2>
            <?php if (isset($_GET['message'])): ?>
                <p class="success-message"><?php echo $_GET['message']; ?></p>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <p class="error-message"><?php echo $_GET['error']; ?></p>
            <?php endif; ?>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <div class="manage-section">
                    <h3>Add Person</h3>
                    <form action="add_person.php" method="post">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" required>
                        <label for="role">Role:</label>
                        <select id="role" name="role" required>
                            <option value="user">User</option>
                            <option value="manager">Manager</option>
                            <option value="admin">Admin</option>
                        </select>
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                        <label for="evaluation">Evaluation:</label>
                        <input type="number" id="evaluation" name="evaluation" required>
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" required></textarea>
                        <button type="submit">Add Person</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="manage-section">
                <h3>Add V-Bank</h3>
                <form action="add_vbank.php" method="post">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" required>
                    <label for="date">Date:</label>
                    <input type="date" id="date" name="date" required>
                    <button type="submit">Add V-Bank</button>
                </form>
            </div>
            <div class="manage-section">
                <h3>Add Group</h3>
                <form action="add_group.php" method="post">
                    <label for="vbank_id">V-Bank:</label>
                    <select id="vbank_id" name="vbank_id" required onchange="fetchAvailableLeaders(this.value)">
                        <option value="">Select V-Bank</option>
                        <?php foreach ($vbanks as $vbank): ?>
                            <option value="<?php echo $vbank['id']; ?>"><?php echo $vbank['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="leader_id">Leader:</label>
                    <select id="leader_id" name="leader_id" required>
                        <option value="">Select Leader</option>
                    </select>
                    <label for="grade">Grade:</label>
                    <select id="grade" name="grade">
                        <option value="">Select Grade</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                    <button type="submit">Add Group</button>
                </form>
            </div>
            <div class="manage-section">
                <h3>Add Student to Group</h3>
                <form action="add_student_to_group.php" method="post">
                    <label for="vbank_id">V-Bank:</label>
                    <select id="vbank_student_id" name="vbank_student_id" required onchange="fetchGroups(this.value)">
                        <option value="">Select V-Bank</option>
                        <?php foreach ($vbanks as $vbank): ?>
                            <option value="<?php echo $vbank['id']; ?>"><?php echo $vbank['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <label for="group_id">Group:</label>
                    <select id="group_id" name="group_id" required onchange="fetchStudents(document.getElementById('vbank_student_id').value, this.value)">
                        <option value="">Select Group</option>
                    </select>
                    <label for="student_id">Student:</label>
                    <select id="student_id" name="student_id" required>
                        <option value="">Select Student</option>
                    </select>
                    <button type="submit">Add Student</button>
                </form>
                
                <table id="group_students_table" style="display: none;">
                    <tr>
                        <th>Student ID</th>
                        <th>Student Name</th>
                    </tr>
                </table>
            </div>
            <div class="manage-section">
                <h3>Manage V-Bank</h3>
                <label for="vbank_select">Select V-Bank:</label>
                <select id="vbank_select" onchange="fetchVBankDetails(this.value)">
                    <option value="">Select V-Bank</option>
                    <?php foreach ($vbanks as $vbank): ?>
                        <option value="<?php echo $vbank['id']; ?>"><?php echo $vbank['title']; ?></option>
                    <?php endforeach; ?>
                </select>
                <div id="vbank-details-container"></div>
            </div>
        </div>
    </main>
    <?php include '../footer.html'; ?>
</body>
</html>
<?php

$conn->close();
?>

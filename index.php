<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet</title>
    <link rel="stylesheet" href="styles.css">
    <script src="script.js" defer></script>
</head>
<body>
    <?php include 'config/constants.php'; ?>
    <?php include 'header.php'; ?>
    <main class="main-container">
        <?php
        
        include 'config/variables.php';

        
        $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        
        $current_date = date('Y-m-d');

        
        $sql = "SELECT id, title, date FROM vbanks WHERE date > '$current_date' ORDER BY date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            
            while($row = $result->fetch_assoc()) {
                echo "<div class='panel'>";
                echo "<h2>Ongoing V-Bank: " . $row["title"]. "  " . $row["date"]. "</h2>";

                $vbank_id = $row["id"];
                $sql = "SELECT id, grade, leader_id FROM `groups` WHERE vbank_id = " . $vbank_id;
                $groups = $conn->query($sql);

                if ($groups->num_rows > 0) {
                    
                    $sql = "SELECT SUM(bets.amount) AS total_bets FROM bets JOIN `groups` ON bets.group_id = `groups`.id WHERE `groups`.vbank_id = " . $vbank_id;
                    $total_bets_result = $conn->query($sql);
                    $total_bets = $total_bets_result->fetch_assoc()["total_bets"];

                    while($group = $groups->fetch_assoc()) {
                        $sql = "SELECT name FROM students WHERE id = " . $group["leader_id"];
                        $leader_result = $conn->query($sql);
                        $leader = $leader_result->fetch_assoc()["name"];

                        
                        $sql = "SELECT AVG(students.evaluation) AS avg_evaluation FROM students JOIN student_group ON students.id = student_group.student_id WHERE student_group.group_id = " . $group["id"];
                        $avg_evaluation_result = $conn->query($sql);
                        $avg_evaluation = $avg_evaluation_result->fetch_assoc()["avg_evaluation"];

                        
                        $sql = "SELECT SUM(amount) AS group_bets FROM bets WHERE group_id = " . $group["id"];
                        $group_bets_result = $conn->query($sql);
                        $group_bets = $group_bets_result->fetch_assoc()["group_bets"];
                        $group_bets = $group_bets > 0 ? $group_bets : 0.01; 
                        $probability = $total_bets > 0 ? $group_bets / $total_bets : 0;
                        $coefficient = $probability > 0 ? round(1 / $probability, 2) . 'x' : '0x';

                        echo "<div class='group-info' onclick='toggleGroupMembers(" . $group["id"] . ")'>";
                        echo "<span>Group of " . $leader . "</span>";
                        echo "<div><span class='coefficient'>" . $coefficient . "</span>";
                        echo "<span id='toggle-symbol-" . $group["id"] . "' class='toggle-symbol'>►</span></div>";
                        echo "</div>";

                        echo "<div id='group-members-" . $group["id"] . "' class='group-members' style='display: none;'>";
                        echo "<p>Avg Evaluation: " . round($avg_evaluation, 2) . "</p>";
                        echo "<p>Members: </p>";
                        $sql = "SELECT students.id, students.name, students.evaluation FROM students JOIN student_group ON students.id = student_group.student_id WHERE student_group.group_id = " . $group["id"];
                        $members_result = $conn->query($sql);
                        if ($members_result->num_rows > 0) {
                            echo "<ul style='margin-left: 10px;'>";
                            while($member = $members_result->fetch_assoc()) {
                                echo "<li>" . $member["name"] . "<a href='students.php#s" . $member["id"] . "' class='more-info-btn'>▶</a><span class='evaluation'>" . $member["evaluation"] . "</span></li>";
                            }
                            echo "</ul>";
                        } else {
                            echo "<p>No members in this group.</p>";
                        }
                        if (isset($_SESSION['student_id'])) {
                            echo "<a href='place_bet.php?group_id=" . $group["id"] . "' class='btn place-bet-btn new-button-style'>Place Bet</a>";
                        } else {
                            echo "<p>Please <a href='login.php'>log in</a> to place bets.</p>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "<p>No groups are participating in this V-Bank.</p>";
                }

                
                echo "<div class='comments-section'>";
                echo "<h3>Comments</h3>";

                $sql = "SELECT comments.id, comments.content, (SELECT COUNT(*) FROM likes WHERE likes.comment_id = comments.id LIMIT 1) AS likes, students.name AS author_name
                        FROM comments
                        JOIN students ON comments.author_id = students.id
                        WHERE comments.vbank_id = " . $vbank_id . "
                        ORDER BY likes DESC, comments.date DESC";
                $comments_result = $conn->query($sql);

                if ($comments_result->num_rows > 0) {
                    $comments = [];
                    while($comment = $comments_result->fetch_assoc()) {
                        $comments[] = $comment;
                    }

                    if (count($comments) > 0) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . $comments[0]["author_name"] . ":</strong> " . $comments[0]["content"] . "</p>";
                        echo "<button class='like-btn' onclick='likeComment(" . $comments[0]["id"] . ")'>❤️ " . $comments[0]["likes"] . "</button>";
                        echo "</div>";
                    }

                    echo "<div id='all-comments' style='display: none;'>";
                    for ($i = 1; $i < count($comments); $i++) {
                        echo "<div class='comment'>";
                        echo "<p><strong>" . $comments[$i]["author_name"] . ":</strong> " . $comments[$i]["content"] . "</p>";
                        echo "<button class='like-btn' onclick='likeComment(" . $comments[$i]["id"] . ")'>❤️ " . $comments[$i]["likes"] . "</button>";
                        echo "</div>";
                    }
                    echo "</div>";

                    if (count($comments) > 1) {
                        echo "<button id='show-more-comments' class='show-more-btn link-btn' onclick='showAllComments()'>Show More Comments</button>";
                        echo "<button id='hide-comments' class='hide-btn link-btn' onclick='hideAllComments()' style='display: none;'>Hide Comments</button>";
                    }
                } else {
                    echo "<p>No comments yet.</p>";
                }

                
                if (isset($_SESSION['student_id'])) {
                    echo "<form id='comment-form' action='post_comment.php' method='POST'>";
                    echo "<input type='hidden' name='vbank_id' value='" . $vbank_id . "'>";
                    echo "<textarea name='content' placeholder='Write a comment...' required></textarea>";
                    echo "<button type='submit'>Post Comment</button>";
                    echo "</form>";
                } else {
                    echo "<p>Please <a href='login.php'>log in</a> to post a comment.</p>";
                }

                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<h2>No ongoing V-Bank is coming</h2>";
        }

        
        $conn->close();
        ?>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>

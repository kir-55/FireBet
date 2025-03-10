<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About FireBet</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="about.css">
</head>
<body>
    <?php include '../header.php'; ?>
    <main class="main-container">
        <div class="documentation-container">
            <h1>About FireBet</h1>
            <section>
                <h2>Project Overview</h2>
                <p>FireBet is a web-based betting platform where users can place bets on various groups participating in virtual banks (V-Banks). The platform allows users to view ongoing V-Banks, place bets on groups, and see the results of their bets.</p>
            </section>
            <section>
                <h2>How It Works</h2>
                <p>Users can log in to the platform and view the ongoing V-Banks. Each V-Bank consists of multiple groups, each led by a student. Users can place bets on these groups based on their performance evaluations.</p>
                <p>Once the V-Bank is completed, the results are processed, and the profit or loss for each bet is calculated based on the group's performance and the total bets placed.</p>
            </section>
            <section>
                <h2>Profit and Loss Calculation</h2>
                <p>The profit or loss for each bet is calculated as follows:</p>
                <ul>
                    <li><strong>Total Bets:</strong> The total amount of bets placed on all groups in the V-Bank.</li>
                    <li><strong>Winning Groups:</strong> The groups with the highest grades in the V-Bank.</li>
                    <li><strong>Total Bets on Winning Groups:</strong> The total amount of bets placed on the winning groups.</li>
                    <li><strong>Coefficient:</strong> The ratio of the total bets to the total bets on the winning groups.</li>
                    <li><strong>Profit/Loss:</strong> For winning bets, the profit is calculated as the bet amount multiplied by the coefficient minus the bet amount. For losing bets, the loss is equal to the bet amount.</li>
                </ul>
                <p>The formula for calculating the profit or loss is:</p>
                <pre><code>Profit/Loss = Bet Amount * (Coefficient - 1)</code></pre>
                <p>If the bet is on a losing group, the profit/loss is simply the negative of the bet amount.</p>
            </section>
            <section>
                <h2>Technologies Used</h2>
                <ul>
                    <li>PHP</li>
                    <li>MySQL</li>
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>JavaScript</li>
                </ul>
            </section>
        </div>
    </main>
    <?php include '../footer.html'; ?>
</body>
</html>

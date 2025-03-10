<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FireBet - Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'config/constants.php'; ?>
    <?php include 'header.php'; ?>
    <main>
        <div class="login-container">
            <h2>Login</h2>
            <form action="login_process.php" method="post">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
    <?php include 'footer.html'; ?>
</body>
</html>

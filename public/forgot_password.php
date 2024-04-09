<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Forgot Password</h2>
        <p>Enter your email address below to receive instructions for resetting your password.</p>
        <form action="/api/email/send_reset_password" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <button type="submit">Reset Password</button>
            </div>
        </form>
        <div class="form-group">
            <p>Remembered your password? <a href="login.php">Log in</a></p>
        </div>
    </div>
</body>
</html>

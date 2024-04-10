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
        <p>Enter the email address you used to create your account, and we'll send you a link to reset your password. If your email address is registered in our system you will receive the email..</p>
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

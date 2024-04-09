<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isset($_GET['email']))
    send400("login.php", "url_is_broken");

$email = $_GET['email'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Reset Password</h2>
        <p>Enter your new password below:</p>
        <form action="/api/password/update" method="POST">
            <input type="hidden" name="email" value="<?php echo $email;?>">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required>
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

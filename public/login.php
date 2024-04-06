<?php

session_start();
if(isset($_SESSION['email']) && isset($_SESSION['name']))
    header("Location: dashboard.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>

<form action="/api/login" method="post">
    <label for="email">Email: </label><br>
    <input type="text" id="email" name="email" placeholder="enter your email" required><br>
    <label for="password">Password: </label><br>
    <input type="password" id="password" name="password" placeholder="enter your password" required><br><br>

    <button type="submit" name="submit">Login</button>
</form>

Don't have an account, <a href="signup.php">signup</a>

</body>
</html>

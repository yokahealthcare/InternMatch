<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
</head>
<body>

<form action="/api/email/send_reset_password" method="post">
    <label for="email">Email:</label><br>
    <input type="text" name="email" id="email" required><br>
    <button type="submit" name="submit">Send Forgot Password Email</button>
</form>

</body>
</html>

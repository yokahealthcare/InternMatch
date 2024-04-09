<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isset($_GET['email']))
    send400("login.php", "url_is_broken");
$email = $_GET['email']
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
</head>
<body>

<form action="/api/password/update" method="post">
    <input type="hidden" name="email" value="<?php echo $email;?>">

    <label for="password">Password:</label><br>
    <input type="password" name="password" id="password" required>

    <button type="submit" name="submit">Reset Password</button>
</form>

</body>
</html>

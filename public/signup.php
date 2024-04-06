<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Signup</title>
</head>
<body>

<form action="/api/signup" method="post">
    <label for="name">Name: </label><br>
    <input type="text" id="name" name="name" placeholder="enter your name" required><br>
    <label for="email">Email: </label><br>
    <input type="text" id="email" name="email" placeholder="enter your email" required><br>
    <label for="password">Password: </label><br>
    <input type="password" id="password" name="password" placeholder="enter your password" required><br><br>

    <button type="submit" name="submit">Signup</button>
</form>

Already have an account, <a href="login.php">login</a>

</body>
</html>

<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isLogged())
    header("Location: login.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Vancancy</title>
</head>
<body>

<form action="/api/vacancy/create" method="post">
    <label for="title">Title:</label><br>
    <input type="text" name="title" id="title" required><br>
    <label for="description">Description:</label><br>
    <input type="text" name="description" id="description" required><br>

    <label>Status:</label><br>
    <input type="radio" name="status" id="open" value="open" checked>
    <label for="open">Open</label><br>
    <input type="radio" name="status" id="close" value="close">
    <label for="close">Close</label><br>

    <input type="hidden" name="account" value="<?php echo getSessionEmail()?>" required>

    <button type="submit" name="submit">Create Vacancy</button>
</form>

</body>
</html>

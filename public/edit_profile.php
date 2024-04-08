<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/util.php';

if(!isLogged())
    header("Location: login.php");

$profile = json_decode(fetchProfile(getSessionEmail()), true)[0];
$email = $profile['email'];
$name = $profile['name'];
$aboutme = $profile['aboutme'];
$address = $profile['address'];
$linkedin = $profile['linkedin'];
$verified = $profile['verified'];

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Profile</title>
</head>
<body>

<form action="/api/account/update" method="post">
    <label for="email">Email:</label><br>
    <input type="email" name="email" id="email" value="<?php echo getSessionEmail();?>" disabled><br>
    <label for="name">Name:</label><br>
    <input type="text" name="name" id="name" value="<?php echo getSessionName();?>" required><br>
    <label for="aboutme">About me:</label><br>
    <input type="text" name="aboutme" id="aboutme" value="<?php echo $aboutme;?>"><br>
    <label for="address">Address:</label><br>
    <input type="text" name="address" id="address" value="<?php echo $address;?>"><br>
    <label for="linkedin">Linkedin:</label><br>
    <input type="text" name="linkedin" id="linkedin" value="<?php echo $linkedin;?>"><br>

    <button type="submit" name="submit">Update Profile</button>
</form>

</body>
</html>

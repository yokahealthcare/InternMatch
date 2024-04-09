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
    <title>Profile</title>
</head>
<body>

<h1>Profile</h1>

<?php


    echo "======================= <br>";
    echo "EMAIL : $email <br>";
    echo "Name : $name <br>";
    echo "About me : $aboutme <br>";
    echo "Address : $address <br>";
    echo "Linked : $linkedin <br>";
    echo "Verified : $verified <br>";
    echo "======================= <br>";

?>
<br>
<a href="edit_profile.php">Edit Profile</a>
<br><br>
<a href="dashboard.php">Back to dashboard</a>

</body>
</html>

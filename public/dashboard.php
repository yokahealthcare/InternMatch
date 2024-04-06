<?php

session_start();
if(!(isset($_SESSION['email']) && isset($_SESSION['name'])))
    header("Location: login.php");

$url = 'http://localhost:8888/api/vacancy/fetch';
// 1. initialize cURL
$ch = curl_init();

// 2. set the URL to access
curl_setopt($ch, CURLOPT_URL, $url);

// 3. set cURL to return as a string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 4. execute cURL and store the result
$output = curl_exec($ch);

// 5. close cURL after use
curl_close($ch);

// 6. print the output
print $output;

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>
<body>


</body>
</html>

<?php
//
//require __DIR__ . '/../vendor/autoload.php';
//require __DIR__ . '/util.php';
//
//$url = "http://localhost:8888/api/vacancy/fetch";
//file_get_contents($url);


// Define the URL and data
$url = 'https://reqres.in/api/users?page=2';
$data = [];

// Prepare POST data
$options = [
	'http' => [
		'method' => 'GET',
		'header' => 'Content-type: application/x-www-form-urlencoded',
	],
];

// Create stream context
$context = stream_context_create($options);

// Perform POST request
$response = file_get_contents($url, false, $context);

// Display the response
echo $response;




<?php

function encrypt_password($plain_password): string
{
    return password_hash($plain_password, PASSWORD_DEFAULT);
}

function send_request($api_path, $json_payload)
{
    $url = 'localhost:8888' . $api_path;

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $json_payload,
        CURLOPT_HTTPHEADER => array('Content-Type: application/json'),
        CURLOPT_RETURNTRANSFER => true
    );

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if ($response === false) {
        die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
    }

    curl_close($ch);

    echo 'Response:' . "\n";
    var_dump($response);


}
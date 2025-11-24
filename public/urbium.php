<?php

$username = $_POST['form']['username'];
$password = $_POST['form']['password'];

$ch = curl_init('https://sw.urbium.cz/eurobydleni-login');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, array(
    'username' => $username,
    'password' => $password,
));

$response = json_decode(curl_exec($ch), true);

if(isset($response['ok']) && $response['ok']) {
    echo json_encode(array(
        'form' => array(
            'status' => 1,
            'message' => 'Probíhá přesměrování'
        ),
        'replace' => array(
            'url' => $response['redirect']
        )
    ));
}else{
    echo json_encode(array(
        'form' => array(
            'status' => 2,
            'message' => 'Neplatné údaje'
        ),
    ));
}

die();


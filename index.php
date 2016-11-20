<?php

$username = 'username';
$password = 'password';
$channel = 76298;

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/Authentication.php';

//use Authentication;
use WebSocket\Client as WsClient;
use WebSocket\ConnectionException;

// Authenticate
$auth = new Authentication();
$auth->login($username, $password);

// Socket request
$client = new WsClient("wss://constellation.beam.pro/?jwt=" . $auth->jwtToken);

echo 'SENT:     {"id":666,"type":"method","method":"watching","params":{"watching":true,"channel":' . $channel . ',"adblock":false}}' . "\n";
$client->send('{"id":666,"type":"method","method":"watching","params":{"watching":true,"channel"::' . $channel . ',"adblock":false}}');

while (true) {
    try {
        $data = $client->receive();
        if ($data != '') {
            echo "RECEIVED: " . $data . "\n";
        }
    } catch (ConnectionException $e) {

    }
}


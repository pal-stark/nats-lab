#!/usr/bin/php
<?php

use Basis\Nats\Client;
//use Basis\Nats\Message\Payload;

$configuration = require_once '../config.php';

$group = $argc > 1 ? (string)$argv[1] : null;

try {

    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }
    if ($group) {
        $client->subscribeQueue('test.message', $group, function ($message) {
            messageHaqndler($message);
        });

    } else {
        $client->subscribe('test.message', function ($message) {
            messageHaqndler($message);
        });
    }
    do {
        $client->process();
    } while (1);

} catch (Exception $e){
    echo $e->getMessage(), PHP_EOL;
}

function messageHaqndler($message){
    echo $message->subject, ': ', $message->body, PHP_EOL;
    print_r($message->headers);
}

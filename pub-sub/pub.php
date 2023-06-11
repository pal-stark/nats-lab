#!/usr/bin/php
<?php

use Basis\Nats\Client;
use Basis\Nats\Message\Payload;

require_once '../config.php';

try {


    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }
//
    $count = $argc > 1 ? (int)$argv[1] : 0;
    $delay = $argc > 2 ? (int)$argv[2] : 0;

    $replayTo = '';


    $i = 0;
    do {
        $i++;
        echo $message = "Message {$i}";
        echo PHP_EOL;

        $payload = new Payload($message, [
            'Id' => $i
        ]);
        //Уникальный идентификатор для маркировки сообщения. Не все клиенты могут с ним работать.
        $replayTo = uniqid('invest-', true);

        $client->publish('test.message', $payload, $replayTo);
        sleep($delay);

    } while ($count ? $i < $count : 1);

} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}
#!/usr/bin/php
<?php

use Basis\Nats\Client;
use Basis\Nats\Message\Payload;

$configuration = require_once '../config.php';

$options = getopt("s:t:c:d:m:");
$stream = isset($options['s']) ? (string)$options['s'] : 'test';
$count = isset($options['c']) ? (int)$options['c'] : 0;
$topic = isset($options['t']) ? (string)$options['t'] : 'test.message.rr';
$delay = isset($options['d']) ? (int)$options['d'] : 0;
$method = isset($options['m']) ? (string)$options['m'] : 's';

try {
    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }

    $i = 0;
    do {
        $i++;
        echo $message = "Message {$i}";
        echo PHP_EOL;

        $payload = new Payload($message, ['Id' => $i]);
        switch ($method) {
            case 's':
                $response = $client->dispatch($topic, $payload, 1000);
                handleResponse($response);
                break;
            case 'a':
            default:
                $client->request($topic, $payload, function ($response) {
                    handleResponse($response);
                });
        }
        if ($delay) {
            sleep($delay);
        }

    } while ($count ? $i < $count : 1);

} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}

function handleResponse($response)
{
    echo $response->body, PHP_EOL;
}

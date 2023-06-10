<?php

use Basis\Nats\Client;
use Basis\Nats\Message\Payload;

require_once '../config.php';


$client = new Client($configuration);
if ($client->ping()) {
    echo 'connected', PHP_EOL;
} else {
    die('error to connect to nats server');
}
//
$count = $argc > 1 ? (int)$argv[1] : 0;
$delay = $argc > 2 ? (int)$argv[2] : 0;

$i = 0;
do {
    $i++;
    echo $message = "Message {$i}";
    echo PHP_EOL;


    $payload = new Payload($message, [
        'Id' => $i
    ]);

    $client->publish('test.message', $payload);
    sleep($delay);

} while ($count ? $i < $count : 1);

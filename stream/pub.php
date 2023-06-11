#!/usr/bin/php
<?php

use Basis\Nats\Client;
use Basis\Nats\Message\Payload;
use Basis\Nats\Stream\RetentionPolicy;
use Basis\Nats\Stream\StorageBackend;

$configuration = require_once '../config.php';

$replayTo = 'invest';
$count = $argc > 1 ? (int)$argv[1] : 0;
$delay = $argc > 2 ? (int)$argv[2] : 0;
$topic = $argc > 3 ? (string)$argv[3] : 'test.message';

$stream = $argc > 4 ? (string)$argv[4] : 'test';



try {
    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }

    //$accountInfo = $client->getApi()->getInfo(); // account_info_response object

    $stream = $client->getApi()->getStream($stream);

    $stream->getConfiguration()
        ->setRetentionPolicy(RetentionPolicy::WORK_QUEUE)
        ->setStorageBackend(StorageBackend::MEMORY)
        ->setSubjects(['test.*']);
    $stream->create();

    $i = 0;
    do {
        $i++;
        echo "$topic:";
        echo $message = "Message {$i}";
        echo PHP_EOL;

        $payload = new Payload($message, [
            'Id' => $i
        ]);

        $stream->put($topic, $payload);

        sleep($delay);

    } while ($count ? $i < $count : 1);
} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}
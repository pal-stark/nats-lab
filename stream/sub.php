#!/usr/bin/php
<?php

use Basis\Nats\Client;
use Basis\Nats\Consumer\Configuration as ConsumerConfiguration;
use Basis\Nats\Consumer\DeliverPolicy;

//use Basis\Nats\Message\Payload;

$configuration = require_once '../config.php';

$options = getopt("s:t:c:p");

$stream = isset($options['s']) ? $options['s'] : 'test';
$consumerId =  isset($options['c']) ? $options['c'] : '';
$topic = isset($options['t']) ? $options['t'] : '';

try {

    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }

    $stream = $client->getApi()->getStream($stream);
    $subscriber = $stream->getConsumer($consumerId);

    if($topic) {
        $subscriber->getConfiguration()->setSubjectFilter($topic);
    }
    $subscriber->handle(function ($message) {
        messageHaqndler($message);
    });

    do {
        $subscriber->batch();
    } while (1);

} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}

function messageHaqndler($message)
{
    echo $message->subject, ': ', $message->body, PHP_EOL;
    print_r($message->headers);
}

#!/usr/bin/php
<?php
use Basis\Nats\Client;
require_once '../config.php';

$options = getopt("m:t:");
$topic = isset($options['t']) ? $options['t'] : 'test.message.rr';

try {

    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }
        $client->subscribe($topic, function ($message) {
            return messageHaqndler($message );
        });
    do {
        $client->process();
    } while (1);

} catch (Exception $e){
    echo $e->getMessage(), PHP_EOL;
}

function messageHaqndler($message){
    echo $message->subject, ': ', $message->body, PHP_EOL;
    return $message->body . ': + result';
}

#!/usr/bin/php
<?php
use Basis\Nats\Client;
use Basis\Nats\Message\Payload;

$configuration = require_once '../config.php';

$options = getopt('s:b:c:d:m:k:v:a:');

$stream = isset($options['s']) ? (string)$options['s'] : 's';
$bucketName = isset($options['b']) ? (string)$options['b'] : 'bucket';
$act = isset($options['a']) ? (string)$options['a'] : 'put';
$key = isset($options['k']) ? (string)$options['k'] : '';
$value = isset($options['v']) ? (string)$options['v'] : '';
$rev = isset($options['r']) ? (string)$options['r'] : '1.0';

try {

    $client = new Client($configuration);
    if ($client->ping()) {
        echo 'connected', PHP_EOL;
    } else {
        die('error to connect to nats server');
    }
//
    $bucket = $client->getApi()->getBucket($bucketName );

    echo $act , PHP_EOL;

    switch($act){
        case 'get':
            echo "$key:", $bucket->get($key), PHP_EOL;
            break;
        case 'update':
            $bucket->update($key, $value, $rev);
            break;
        case 'del':
            $bucket->delete($key);
            break;
        case 'purge':
            $bucket->purge($key);
            break;
        case 'info':
            print_r($bucket->getStatus());
            break;
        case 'put':
            $bucket->put($key, $value);
    }

} catch (Exception $e) {
    echo $e->getMessage(), PHP_EOL;
}

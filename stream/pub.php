<?php

use Basis\Nats\Client;

$configuration = require_once '../config.php';

$client = new Client($configuration);
if ($client->ping()) {
    echo 'connected', PHP_EOL;
} else {
    die('error to connect to nats server');
}

<?php
use Basis\Nats\Configuration;

include_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$cnf = [
    'host' => env('NATS_HOST','localhost'),
    'port' =>  env('NATS_PORT',4222),
    'jwt' => null,
    'lang' => 'php',
    'pass' => null,
    'pedantic' => false,

    'reconnect' => true,
    'timeout' => 1,
    'token' => null,
    'user' => null,
    'nkey' => null,
    'verbose' => false,
    'version' => 'dev',
];


$configuration = new Configuration($cnf);

return $configuration;

function env($key, $val = ''){
    $nv = getenv($key);
    return $nv ? $nv : $val;
}

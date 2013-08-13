<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$result = array();
$file = realpath(__DIR__ . "/../web/update.csv");
if (file_exists($file) && ($handle = fopen($file, 'r')) !== false) {

    $context = new ZMQContext();
    $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    $socket->connect("tcp://localhost:5555");

    while (($data = fgetcsv($handle)) !== false) {
        $socket->send(json_encode(array('channel' => $data[0], 'attributes' => json_decode($data[1], true))));
    }
    fclose($handle);
//    unlink($file);
}

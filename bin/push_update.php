<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$url = 'http://example.com/user:1#changed';
$attributes = array(
    'first_name' => 'Robb',
    'last_name' => 'Stark',
    'when' => time()
);

$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
$socket->connect("tcp://localhost:5555");

$socket->send(json_encode(array('url' => $url, 'attributes' => $attributes)));

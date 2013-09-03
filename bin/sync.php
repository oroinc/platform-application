<?php

set_error_handler(
    function () {
        fwrite(STDERR, vsprintf("%d: %s in %s on line %s\n", func_get_args()));
    }
);


use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use Oro\SyncBundle\Server\Sync;

    require dirname(__DIR__) . '/vendor/autoload.php';
    require_once dirname(__DIR__) . '/src/Oro/src/Oro/Bundle/SyncBundle/Server/Sync.php';

    $loop   = React\EventLoop\Factory::create();
    $sync = new Sync;

    // Listen for the web server to make a ZeroMQ push after an ajax request
    $context = new React\ZMQ\Context($loop);
    $pull = $context->getSocket(ZMQ::SOCKET_PULL);
    $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
    $pull->on('message', array($sync, 'onUpdate'));

    // Set up our WebSocket server for clients wanting real-time updates
    $webSock = new React\Socket\Server($loop);
    $webSock->listen(8000, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
    $server = new IoServer(
        new WsServer(
            new WampServer($sync)
        ),
        $webSock
    );

    $loop->run();

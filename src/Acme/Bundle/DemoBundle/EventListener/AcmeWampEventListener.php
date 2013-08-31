<?php
namespace Acme\Bundle\DemoBundle\EventListener;

use Symfony\Component\Security\Core\SecurityContextInterface;

use JDare\ClankBundle\Event\ClientEvent;
use JDare\ClankBundle\Event\ClientErrorEvent;

class AcmeWampEventListener
{
    /**
     * @var SecurityContextInterface
     */
    protected $security;

    /**
     * DI setter for security context
     *
     * @param SecurityContextInterface $security
     */
    public function __construct(SecurityContextInterface $security)
    {
        $this->security = $security;
    }

    /**
     * Called whenever a client connects
     *
     * @param ClientEvent $event
     */
    public function onClientConnect(ClientEvent $event)
    {
        $conn = $event->getConnection();

        echo $conn->resourceId . " connected" . PHP_EOL;
    }

    /**
     * Called whenever a client disconnects
     *
     * @param ClientEvent $event
     */
    public function onClientDisconnect(ClientEvent $event)
    {
        $conn = $event->getConnection();

        echo $conn->resourceId . " disconnected" . PHP_EOL;
    }

    /**
     * Called whenever a client errors
     *
     * @param ClientErrorEvent $event
     */
    public function onClientError(ClientErrorEvent $event)
    {
        $conn = $event->getConnection();
        $e = $event->getException();

        echo "connection error occurred: " . $e->getMessage() . PHP_EOL;
    }
}
<?php

namespace Acme\Bundle\DemoBundle\Wamp;

use JDare\ClankBundle\Topic\TopicInterface;

use Ratchet\ConnectionInterface as Conn;
use Ratchet\Wamp\Topic;

class AcmeTopic implements TopicInterface
{
    /**
     * This will receive any Subscription requests for this topic.
     *
     * @param Conn  $conn
     * @param Topic $topic
     */
    public function onSubscribe(Conn $conn, $topic)
    {
        // this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast($conn->resourceId . ' has joined ' . $topic->getId());
    }

    /**
     * This will receive any UnSubscription requests for this topic.
     *
     * @param Conn  $conn
     * @param Topic $topic
     */
    public function onUnSubscribe(Conn $conn, $topic)
    {
        // this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast($conn->resourceId . ' has left ' . $topic->getId());
    }


    /**
     * This will receive any Publish requests for this topic.
     *
     * @param  Conn       $conn
     * @param  Topic      $topic
     * @param  type       $event
     * @param  array      $exclude
     * @param  array      $eligible
     */
    public function onPublish(Conn $conn, $topic, $event, array $exclude, array $eligible)
    {
        /*
        $topic->getId() will contain the FULL requested uri, so you can proceed based on that

        e.g.

        if ($topic->getId() == 'acme/channel/shout')
            //shout something to all subs.
        */

        $session = $conn->Session;
        $user    = $session->get('user');

        $topic->broadcast(
            sprintf(
                'Server (conn #%s) handled topic (%s) publishing from "%s": %s',
                $conn->resourceId,
                $topic->getId(),
                is_object($user) ? $user->getUsername() : 'Guest',
                isset($event['msg']) ? $event['msg'] : (string) $event
            )
        );
    }
}
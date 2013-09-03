<?php

namespace Acme\Bundle\DemoBundle\Wamp;

use Ratchet\ConnectionInterface as Conn;

class AcmeRpc
{
    /**
     * Adds the params together
     *
     * Note: $conn isnt used here, but contains the connection of the person making this request.
     *
     * @param  Conn  $conn
     * @param  array $params
     * @return int
     */
    public function getUsername(Conn $conn, $params)
    {
        $session = $conn->Session;
        $user    = $session->get('user');

        return array(
            sprintf(
                'User identified as "%s", session name is "%s", session id: %s',
                is_object($user) ? $user->getUsername() : 'Guest',
                $session->getName(),
                $session->getId()
            )
        );
    }
}
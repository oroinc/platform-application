<?php

namespace Acme\Bundle\DemoBundle\Wamp;

use Ratchet\ConnectionInterface as Conn;

class AcmeRpc
{
    /**
     * Simply return current session username
     *
     * @param  Conn  $conn
     * @param  array $params
     * @return int
     */
    public function getUsername(Conn $conn, $params)
    {
        $token = $conn->security->getToken();
        $user  = $token ? $token->getUser() : null;

        return array(sprintf('User identified as "%s"', is_object($user) ? $user->getUsername() : 'Guest'));
    }
}

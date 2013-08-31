<?php

namespace Acme\Bundle\DemoBundle\Wamp;

use Symfony\Component\Security\Core\SecurityContextInterface;

use Ratchet\ConnectionInterface as Conn;

class AcmeRpc
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
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $conn->Session;

        $token = $this->security->getToken();
        $user  = $token ? $token->getUser() : null;

        return array(
            sprintf(
                'User identified as "%s", session name is "%s", id: %s',
                is_object($user) ? $user->getUsername() : 'Guest',
                $session->getName(),
                $session->getId()
            )
        );
    }
}
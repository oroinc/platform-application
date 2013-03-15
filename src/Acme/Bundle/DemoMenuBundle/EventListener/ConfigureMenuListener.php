<?php
namespace Acme\Bundle\DemoMenuBundle\EventListener;

use Oro\Bundle\NavigationBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    /**
     * @param ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $menu->addChild('Static URL', array('uri' => '#'));
    }
}

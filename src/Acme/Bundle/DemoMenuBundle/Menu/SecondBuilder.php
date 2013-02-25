<?php

namespace Acme\Bundle\DemoMenuBundle\Menu;

use Knp\Menu\ItemInterface;
use Oro\Bundle\MenuBundle\Menu\BuilderInterface;

class SecondBuilder implements BuilderInterface
{
    public function build(ItemInterface $menu, array $options = array(), $alias = null)
    {
        $menu->addChild('Authorized', array('uri' => '/menu/submenu'));
        $menu->addChild(
            'Non authorized',
            array(
                'route' => 'oro_menu_submenu',
                'extras' => array(
                    'translateDomain' => 'menu',
                    'showNonAuthorized' => true
                )
            )
        );

        $users = $menu->getChild('Users');
        $users->addChild('Subitem pos 10', array('route' => 'oro_menu_index', 'extras' => array('position' => 10)));
        $users->addChild('Subitem pos 1', array('route' => 'oro_menu_index', 'extras' => array('position' => 1)))
            ->addChild('Level 3.1', array('route' => 'oro_menu_index'))
            ->addChild('Level 4.1', array('route' => 'oro_menu_index'));
        $users->addChild('Current item', array('route' => 'oro_menu_test'));
    }
}

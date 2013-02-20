<?php

namespace Acme\Bundle\DemoMenuBundle\Menu;

use Knp\Menu\ItemInterface;
use Oro\Bundle\MenuBundle\Menu\BuilderInterface;

class FirstBuilder implements BuilderInterface
{
    public function build(ItemInterface $menu, array $options = array(), $alias = null)
    {
        $menu->setExtra('type', 'navbar');
        $menu->setExtra('brand', 'BAP Dev');
        $menu->setExtra('brandLink', '/menu/test');
        $menu->addChild('Homepage', array('route' => 'oro_menu_index', 'extras' => array('position' => 10)));
        $menu->addChild('Users', array('route' => 'oro_menu_test', 'extras' => array('position' => 2)));
    }
}

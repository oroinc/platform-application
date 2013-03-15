<?php

namespace Acme\Bundle\DemoMenuBundle\Menu;

use Knp\Menu\ItemInterface;
use Oro\Bundle\NavigationBundle\Menu\BuilderInterface;

class LoadBuilder implements BuilderInterface
{
    const FIRST_LEVEL_COUNT = 4;
    const SECOND_LEVEL_COUNT = 25;

    public function build(ItemInterface $menu, array $options = array(), $alias = null)
    {
        for ($i = 0; $i < self::FIRST_LEVEL_COUNT; $i++) {
            $menu->addChild('item-' . $i, array('route' => 'oro_menu_submenu'));
            for ($j = 0; $j < self::SECOND_LEVEL_COUNT; $j++) {
                $menu->getChild('item-' . $i)
                    ->addChild('subitem-' . $i . '-' . $j, array('route' => 'oro_menu_submenu'));
            }
        }
    }
}

<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CsvConfiguration extends AbstractConfiguration
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('params');

        $rootNode->children()
            ->scalarNode('charset')
            ->defaultValue('UTF8')
            ->end()

            ->scalarNode('delimiter')
            ->defaultValue(';')
            ->end()

            ->scalarNode('enclosure')
            ->defaultValue('"')
            ->end()

            ->scalarNode('file_path')
            ->defaultNull()
            ->end()
        ->end();

        return $treeBuilder;
    }

}

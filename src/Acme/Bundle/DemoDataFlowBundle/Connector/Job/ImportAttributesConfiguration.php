<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector\Job;

use Oro\Bundle\DataFlowBundle\Connector\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Job configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportAttributesConfiguration extends AbstractConfiguration
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('params');

        $rootNode->children()
            ->scalarNode('driver')->defaultValue('pdo_mysql')->end()
            ->scalarNode('host')->defaultValue('localhost')->end()
            ->scalarNode('port')->defaultNull()->end()
            ->scalarNode('dbname')->end()
            ->scalarNode('user')->defaultValue('root')->end()
            ->scalarNode('password')->defaultNull()->end()
            ->scalarNode('charset')->defaultValue('UTF8')->end()
            ->scalarNode('table_prefix')->defaultNull()->end()
        ->end();

        return $treeBuilder;
    }

}

<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\AbstractConfiguration;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConfiguration extends AbstractConfiguration
{

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root(self::ROOT_NODE);
        $rootNode
            ->children()
                ->append($this->addDatabaseNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Return database configuration node
     *
     * @return NodeDefinition
     */
    public function addDatabaseNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('database');
        $node
            ->children()
                ->scalarNode('driver')
                ->defaultValue('pdo_mysql')
                ->end()
                ->scalarNode('host')
                ->defaultValue('localhost')
                ->end()
                ->scalarNode('port')
                ->defaultNull()
                ->end()
                ->scalarNode('dbname')
                ->end()
                ->scalarNode('user')
                ->defaultValue('root')
                ->end()
                ->scalarNode('password')
                ->defaultNull()
                ->end()
                ->scalarNode('charset')
                ->defaultValue('UTF8')
                ->end()
                ->scalarNode('table_prefix')
                ->defaultNull()
                ->end()
            ->end();

        return $node;
    }

    /**
     * Return db parameters
     * @return /ArrayAccess
     */
    public function getDatabaseParameters()
    {
        return $this->parameters['database'];
    }

    /**
     * Return table prefix
     * @return string
     */
    public function getTablePrefix()
    {
        return $this->parameters['database']['table_prefix'];
    }

}

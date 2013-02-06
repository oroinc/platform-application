<?php
namespace Acme\Bundle\DemoDataFlowBundle\Connector;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\Processor;

/**
 * Connector configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoConfiguration implements ConfigurationInterface
{

    /**
     * @var \ArrayAccess
     */
    protected $configuration;

    /**
     * @param \ArrayAccess $configuration
     */
    public function __construct($configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Process configuration
     */
    public function process()
    {
        $processor = new Processor();
        $this->configuration = $processor->processConfiguration($this, $this->configuration);

        return $this->configuration;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('connector');

        $rootNode->children()
            ->append($this->addDatabaseNode())
            ->scalarNode('table_prefix')->defaultNull()->end()
        ->end();

        return $treeBuilder;
    }

    /**
     * Return database note definition
     *
     * Inspired by Doctrine\Bundle\DoctrineBundle\DependencyInjection\Configuration
     *
     * @return \Symfony\Component\Config\Definition\Builder\NodeDefinition
     */
    public function addDatabaseNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('database')
            ->children()
                ->scalarNode('driver')->defaultValue('pdo_mysql')->end()
                ->scalarNode('host')->defaultValue('localhost')->end()
                ->scalarNode('port')->defaultNull()->end()
                ->scalarNode('dbname')->end()
                ->scalarNode('user')->defaultValue('root')->end()
                ->scalarNode('password')->defaultNull()->end()
                ->scalarNode('charset')->defaultValue('UTF8')->end()
            ->end();

        return $node;
    }

}

<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * Job configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ImportAttributesConfiguration extends MagentoConfiguration
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
                ->scalarNode('excluded_attributes')->defaultNull()->end()
            ->end();

        return $treeBuilder;
    }

    /**
     * Return excluded attributes
     * @return string
     */
    public function getExcludedAttributes()
    {
        return $this->parameters['excluded_attributes'];
    }

}

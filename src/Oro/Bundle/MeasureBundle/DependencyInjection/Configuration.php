<?php

namespace Oro\Bundle\MeasureBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Measure configuration
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('oro_measure');

        $rootNode->children()
            ->arrayNode('measures_config')
            ->prototype('array')
            ->children()

                // standard unit (used as reference for conversion)
                ->scalarNode('standard')
                ->isRequired()
                ->end()

                // units of this group
                ->arrayNode('units')
                ->prototype('array')
                ->children()
                    ->arrayNode('convert')
                    ->isRequired()
                    ->end()
                    ->scalarNode('format')
                    ->isRequired()
                    ->end()
                ->end()

            ->end();

        return $treeBuilder;
    }
}

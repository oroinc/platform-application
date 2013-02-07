<?php
namespace Acme\Bundle\DemoDataFlowBundle\Configuration;

use Oro\Bundle\DataFlowBundle\Configuration\AbstractConfiguration;
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
        $rootNode = $treeBuilder->root(self::ROOT_NODE);

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

            ->scalarNode('escape')
            ->defaultValue('\\')
            ->end()

            ->scalarNode('file_path')
            ->defaultNull()
            ->end()
        ->end();

        return $treeBuilder;
    }

    /**
     * Return delimiter
     * @return string
     */
    public function getDelimiter()
    {
        return $this->parameters['delimiter'];
    }

    /**
     * Return enclosure
     * @return string
     */
    public function getEnclosure()
    {
        return $this->parameters['enclosure'];
    }

    /**
     * Return delimiter
     * @return string
     */
    public function getEscape()
    {
        return $this->parameters['escape'];
    }

    /**
     * Return file path
     * @return string
     */
    public function getFilePath()
    {
        return $this->parameters['file_path'];
    }

}

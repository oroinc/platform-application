<?php

namespace Acme\Bundle\AssetsConfigBundle\Twig;

use Symfony\Bundle\AsseticBundle\Factory\AssetFactory;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Bundle\AsseticBundle\Twig\AsseticNodeVisitor;

class AcmeAssetsConfigExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Bundle\AsseticBundle\Factory\AssetFactory
     */
    protected $assetsFactory;

    /**
     * @var array
     */
    protected $assets;

    /**
     * @var \Symfony\Component\Templating\TemplateNameParserInterface
     */
    protected $templateNameParser;

    /**
     * @var array
     */
    protected $enabledBundles;

    /**
     * @param AssetFactory                $assetsFactory
     * @param array                       $assets
     * @param TemplateNameParserInterface $templateNameParser
     * @param array                       $enabledBundles
     */
    public function __construct(
        AssetFactory $assetsFactory,
        array $assets,
        TemplateNameParserInterface $templateNameParser,
        $enabledBundles = array()
    ) {

        $this->enabledBundles = $enabledBundles;
        $this->templateNameParser = $templateNameParser;
        $this->assetsFactory = $assetsFactory;
        $this->assets = $assets;
    }

    /**
     * {@inheritDoc}
     */
    public function getTokenParsers()
    {
        return array(
            new AsseticTokenParser($this->assets, $this->assetsFactory),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getNodeVisitors()
    {
        return array(
            new AsseticNodeVisitor($this->templateNameParser, $this->enabledBundles),
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'oro_assetic';
    }
}

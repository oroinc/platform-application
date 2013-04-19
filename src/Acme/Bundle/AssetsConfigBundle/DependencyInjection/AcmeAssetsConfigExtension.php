<?php

namespace Acme\Bundle\AssetsConfigBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Reference;

class AcmeAssetsConfigExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $bundles = $container->getParameter('kernel.bundles');
        $assets = array(
            'css' => array(),
            'js'  => array()
        );

        foreach ($bundles as $bundle) {
            $reflection = new \ReflectionClass($bundle);
            if (is_file($file = dirname($reflection->getFilename()) . '/Resources/config/assets.yml')) {
                $bundleConfig = Yaml::parse(realpath($file));
                if (isset($bundleConfig['css'])) {
                    $assets['css'] = array_merge($assets['css'], $bundleConfig['css']);
                }
                if (isset($bundleConfig['js'])) {
                    $assets['js'] = array_merge($assets['js'], $bundleConfig['js']);
                }
            }
        }

        $container->setParameter('oro_assets.assets_config', $assets);
    }

    public function getAlias()
    {
        return 'acme_assets_config';
    }
}

<?php

use Symfony\Component\Config\Loader\LoaderInterface;

use Oro\Bundle\DistributionBundle\OroKernel;

class AppKernel extends OroKernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new BeSimple\SoapBundle\BeSimpleSoapBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Escape\WSSEAuthenticationBundle\EscapeWSSEAuthenticationBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Bazinga\ExposeTranslationBundle\BazingaExposeTranslationBundle(),
            new Genemu\Bundle\FormBundle\GenemuFormBundle(),
            new A2lix\TranslationFormBundle\A2lixTranslationFormBundle(),

            // BAPP bundles
            new OroPro\Bundle\EwsBundle\OroProfessionalEwsBundle(),

            // BAP Demo bundles
            new Acme\Bundle\DemoBundle\AcmeDemoBundle(),
            new Acme\Bundle\DemoMeasureBundle\AcmeDemoMeasureBundle(),
            new Acme\Bundle\DemoMenuBundle\AcmeDemoMenuBundle(),
            new Acme\Bundle\DemoFlexibleEntityBundle\AcmeDemoFlexibleEntityBundle(),
            new Acme\Bundle\DemoSegmentationTreeBundle\AcmeDemoSegmentationTreeBundle(),
            new Acme\Bundle\DemoGridBundle\AcmeDemoGridBundle(),
            new Acme\Bundle\DemoWindowsBundle\AcmeDemoWindowsBundle(),
            new Acme\Bundle\DemoFilterBundle\AcmeDemoFilterBundle(),
            new Acme\Bundle\DemoAddressBundle\AcmeDemoAddressBundle(),
            new Acme\Bundle\DemoFormBundle\AcmeDemoFormBundle(),
            new Acme\Bundle\DemoWorkflowBundle\AcmeDemoWorkflowBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        if (in_array($this->getEnvironment(), array('test', 'perf'))) {
            $bundles[] = new Oro\Bundle\TestFrameworkBundle\OroTestFrameworkBundle();
        }

        return array_merge($bundles, parent::registerBundles());
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }

    protected function initializeContainer()
    {
        static $first = true;

        if ('test' !== $this->getEnvironment()) {
            parent::initializeContainer();
            return;
        }

        $debug = $this->debug;

        if (!$first) {
            // disable debug mode on all but the first initialization
            $this->debug = false;
        }

        // will not work with --process-isolation
        $first = false;

        try {
            parent::initializeContainer();
        } catch (\Exception $e) {
            $this->debug = $debug;
            throw $e;
        }

        $this->debug = $debug;
    }
}

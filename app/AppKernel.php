<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

use Oro\Bundle\DistributionBundle\OroKernel;

class AppKernel extends OroKernel
{
    public function registerBundles()
    {
        $bundles = array(
            
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

        return array_merge(parent::registerBundles(), $bundles);
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}

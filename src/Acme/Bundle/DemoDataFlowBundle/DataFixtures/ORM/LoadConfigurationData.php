<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Acme\Bundle\DemoDataFlowBundle\Configuration\NewMagentoConfiguration;
use Oro\Bundle\DataFlowBundle\Entity\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
* Load configuration
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadConfigurationData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $format = 'xml';

        // prepare conf
        $magentoConf = new NewMagentoConfiguration();
        $magentoConf->setHost('localhost');
        $magentoConf->setDbname('db_magento1');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($magentoConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Magento 1');
        $configuration->setTypeName(get_class($magentoConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // prepare conf
        $magentoConf = new NewMagentoConfiguration();
        $magentoConf->setHost('127.0.0.1');
        $magentoConf->setDbname('db_magento2');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($magentoConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Magento 2');
        $configuration->setTypeName(get_class($magentoConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // save
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 42;
    }

}
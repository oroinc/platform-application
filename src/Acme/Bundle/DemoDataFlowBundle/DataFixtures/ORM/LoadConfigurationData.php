<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Oro\Bundle\DataFlowBundle\Entity\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\Bundle\DemoDataFlowBundle\Configuration\MagentoConfiguration;
use Acme\Bundle\DemoDataFlowBundle\Configuration\CsvConfiguration;
use Acme\Bundle\DemoDataFlowBundle\Configuration\ImportCustomerConfiguration;
use Acme\Bundle\DemoDataFlowBundle\Configuration\ImportAttributeConfiguration;

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

        // prepare conf Magento
        $magentoConf = new MagentoConfiguration();
        $magentoConf->setHost('localhost');
        $magentoConf->setDbname('db_magento1');
        $magentoConf->setUser('admin');
        $magentoConf->setPassword('mypassword');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($magentoConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Magento 1');
        $configuration->setTypeName(get_class($magentoConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // prepare conf Magento
        $magentoConf = new MagentoConfiguration();
        $magentoConf->setHost('127.0.0.1');
        $magentoConf->setUser('root');
        $magentoConf->setPassword('root');
        $magentoConf->setDbname('magento_ab');
        $magentoConf->setTablePrefix('ab_');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($magentoConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Magento 2');
        $configuration->setTypeName(get_class($magentoConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // prepare conf Import attribute
        $magentoConf = new ImportAttributeConfiguration();
        $magentoConf->setExcludedAttributes('sku,old_id,created_at,updated_at');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($magentoConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Import attributes');
        $configuration->setTypeName(get_class($magentoConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // prepare conf CSV
        $csvConf = new CsvConfiguration();
        $csvConf->setDelimiter(',');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($csvConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Magento CSV');
        $configuration->setTypeName(get_class($csvConf));
        $configuration->setFormat($format);
        $configuration->setData($data);
        $manager->persist($configuration);

        // prepare conf ImportCustomer
        $csvConf = new ImportCustomerConfiguration();
        $csvConf->setFilePath(__DIR__.'/../../Resources/files/export_customers.csv');
        $serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        $data = $serializer->serialize($csvConf, $format);

        // prepare persist
        $configuration = new Configuration();
        $configuration->setDescription('Import customer CSV');
        $configuration->setTypeName(get_class($csvConf));
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

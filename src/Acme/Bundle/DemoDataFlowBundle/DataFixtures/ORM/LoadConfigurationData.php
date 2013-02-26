<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Oro\Bundle\DataFlowBundle\Entity\RawConfiguration;
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
        // prepare magento connector and job configuration

        $magentoConf = new MagentoConfiguration();
        $magentoConf->setHost('127.0.0.1');
        $magentoConf->setUser('root');
        $magentoConf->setPassword('root');
        $magentoConf->setDbname('magento_ab');
        $magentoConf->setTablePrefix('ab_');
        $entity = new RawConfiguration($magentoConf);
        $manager->persist($entity);
        $this->addReference('configuration-magento', $entity);

        $magentoJobConf = new ImportAttributeConfiguration();
        $magentoJobConf->setExcludedAttributes('sku,old_id,created_at,updated_at');
        $entity = new RawConfiguration($magentoJobConf);
        $manager->persist($entity);
        $this->addReference('configuration-import-attribute', $entity);

        // prepare csv connector and job configuration

        $csvConf = new CsvConfiguration();
        $csvConf->setDelimiter(',');
        $entity = new RawConfiguration($csvConf);
        $manager->persist($entity);
        $this->addReference('configuration-csv', $entity);

        $csvJobConf = new ImportCustomerConfiguration();
        $csvJobConf->setFilePath(__DIR__.'/../../Resources/files/export_customers.csv');
        $entity = new RawConfiguration($csvJobConf);
        $manager->persist($entity);
        $this->addReference('configuration-import-customer', $entity);

        // save
        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 40;
    }
}

<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Entity\Configuration;
use Oro\Bundle\DataFlowBundle\Entity\Connector;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
* Load connector
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadConnectorData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // create a connector instance
        $configuration = $this->getReference('configuration-magento');
        $magentoConnector = new Connector();
        $magentoConnector->setDescription('Magento http://mysite.com');
        $magentoConnector->setServiceId('connector.magento');
        $magentoConnector->setConfiguration($configuration);
        $manager->persist($magentoConnector);
        $this->addReference('connector-magento', $magentoConnector);

        // create a csv instance
        $configuration = $this->getReference('configuration-csv');
        $csvConnector = new Connector();
        $csvConnector->setDescription('Csv import');
        $csvConnector->setServiceId('connector.csv');
        $csvConnector->setConfiguration($configuration);
        $manager->persist($csvConnector);
        $this->addReference('connector-csv', $csvConnector);

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 41;
    }
}

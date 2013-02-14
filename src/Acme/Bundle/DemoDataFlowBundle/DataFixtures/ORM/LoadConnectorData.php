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
        $configuration = $this->getReference('magento-configuration');
        $magentoConnector = new Connector();
        $magentoConnector->setConnectorService('connector.magento_catalog');
        $magentoConnector->setConnectorConfiguration($configuration);
        $manager->persist($magentoConnector);

        // create a csv instance
        $configuration = $this->getReference('csv-configuration');
        $csvConnector = new Connector();
        $csvConnector->setConnectorService('connector.csv');
        $csvConnector->setConnectorConfiguration($configuration);
        $manager->persist($csvConnector);

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

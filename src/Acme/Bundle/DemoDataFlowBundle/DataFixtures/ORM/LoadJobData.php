<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
use Oro\Bundle\DataFlowBundle\Entity\Configuration;
use Oro\Bundle\DataFlowBundle\Entity\Job;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
* Load job
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadJobData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // create a job instance
        $conConfiguration = $this->getReference('magento-configuration');
        $jobConfiguration = $this->getReference('import-attribute-configuration');
        $magentoJob = new Job();
        $magentoJob->setConnectorService('connector.magento_catalog');
        $magentoJob->setConnectorConfiguration($conConfiguration);
        $magentoJob->setJobService('job.import_attributes');
        $magentoJob->setJobConfiguration($jobConfiguration);
        $manager->persist($magentoJob);

        // create a job instance
        $conConfiguration = $this->getReference('csv-configuration');
        $jobConfiguration = $this->getReference('import-customer-configuration');
        $csvJob = new Job();
        $csvJob->setConnectorService('connector.csv');
        $csvJob->setConnectorConfiguration($conConfiguration);
        $csvJob->setJobService('job.import_customers');
        $csvJob->setJobConfiguration($jobConfiguration);
        $manager->persist($csvJob);

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

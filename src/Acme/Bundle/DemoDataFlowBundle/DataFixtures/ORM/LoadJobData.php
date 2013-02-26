<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Oro\Bundle\DataFlowBundle\Configuration\ConfigurationInterface;
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
        $configuration = $this->getReference('configuration-import-attribute');
        $connector = $this->getReference('connector-magento');
        $magentoJob = new Job();
        $magentoJob->setServiceId('job.import_attributes');
        $magentoJob->setDescription('Import attributes');
        $magentoJob->setRawConfiguration($configuration);
        $magentoJob->setConnector($connector);
        $manager->persist($magentoJob);

        // create a job instance
        $configuration = $this->getReference('configuration-import-customer');
        $connector = $this->getReference('connector-csv');
        $csvJob = new Job();
        $csvJob->setServiceId('job.import_customers');
        $csvJob->setDescription('Import customers');
        $csvJob->setRawConfiguration($configuration);
        $csvJob->setConnector($connector);
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

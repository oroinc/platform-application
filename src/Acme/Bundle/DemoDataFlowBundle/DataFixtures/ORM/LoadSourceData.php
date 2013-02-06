<?php
namespace Acme\Bundle\DemoDataFlowBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\DataFlowBundle\Entity\Source;
use Oro\Bundle\DataFlowBundle\Source\Configuration\DatabaseConfiguration;


/**
* Load sources
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadSourceData extends AbstractFixture implements OrderedFixtureInterface
{

   /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // add some sources

        $configuration = new DatabaseConfiguration();
        $sourceDb = new Source($configuration);
        $sourceDb->setCode('magento_db');
        $params = array(
            'dbname'   => 'magento',
            'user'     => 'root',
            'password' => 'pwd'
        );
        $sourceDb->setParameters($params);
        $manager->persist($sourceDb);

        /*
        $sourceFtp = new Source();
        $sourceFtp->setCode('magento_ftp');
        $manager->persist($sourceFtp);
        */

        // save
        $manager->flush();

        // keep references for other fixtures
        $this->addReference('source-magento-db', $sourceDb);
//        $this->addReference('source-magento-ftp', $sourceFtp);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 15;
    }

}
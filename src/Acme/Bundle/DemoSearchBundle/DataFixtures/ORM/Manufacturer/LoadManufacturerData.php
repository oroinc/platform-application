<?php
namespace Acme\Bundle\DemoSearchBundle\DataFixtures\ORM\Manufacturer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

/**
* Load manufacturer
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadSearchManufacturerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Flexible entity manager
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get manager
     *
     * @return SimpleManager
     */
    protected function getManufacturerManager()
    {
        return $this->container->get('search_manufacturer_manager');
    }
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $names = array('Dell', 'Lenovo', 'Acer', 'Asus', 'HP');
        $mm = $this->getManufacturerManager();

        // new instances if not exist
        foreach ($names as $name) {
            $manufacturer = $mm->getEntityRepository()->findByName($name);
            if (!$manufacturer) {
                $manufacturer = $mm->createEntity();
                $manufacturer->setName($name);
                $mm->getStorageManager()->persist($manufacturer);
            }
        }

        // save
        $mm->getStorageManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }

}
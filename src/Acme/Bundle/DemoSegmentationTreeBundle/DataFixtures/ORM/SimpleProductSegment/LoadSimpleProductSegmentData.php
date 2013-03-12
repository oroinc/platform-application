<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\DataFixtures\ORM\SimpleProductSegment;

use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleProductSegment;
use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleProduct;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load simple product segment fixtures
 *
 * Execute with "php app/console doctrine:fixtures:load"
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class LoadSimpleProductSegmentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var ObjectManager
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $computer = $this->createSegment('computers');

        $lap1 = $this->createProduct('Laptop 1', 'Nice laptop 1');
        $lap2 = $this->createProduct('Laptop 2', 'Nice laptop 2');
        $lap3 = $this->createProduct('Laptop 3', 'Nice laptop 3');
        $laptops = array($lap1,$lap2,$lap3);

        $laptop = $this->createSegment('laptop', $computer, $laptops);

        $notebooks = array($lap1,$lap3);
        $this->createSegment('notebook', $laptop, $notebooks);

        $this->createSegment('tablet', $computer);
        $this->createSegment('desktop', $computer);
        $this->createSegment('server', $computer);

        $components = $this->createSegment('components');

        $graphics = $this->createSegment('video cards', $components);
        $this->createSegment('nvidia', $graphics);
        $this->createSegment('ati', $graphics);
        $this->createSegment('waterblocks', $graphics);

        $dataStore = $this->createSegment('data storage', $components);
        $this->createSegment('ssd', $dataStore);
        $this->createSegment('internal hard drive', $dataStore);
        $this->createSegment('external hard drive', $dataStore);
        $this->createSegment('storage media cases', $dataStore);
        $this->createSegment('usb keys', $dataStore);
        $disks = $this->createSegment('Disks', $dataStore);

        $this->createSegment('DVD', $disks);
        $this->createSegment('CD', $disks);

        $this->manager->flush();
    }

    /**
     * Create a Segment entity
     *
     * @param string               $title    Title of the segment
     * @param SimpleProductSegment $parent   Parent segment
     * @param array                $products Products that should be associated to this segment
     *
     * @return SimpleProductSegment
     */
    protected function createSegment($title, $parent = null, $products = array())
    {
        $segment = new SimpleProductSegment();
        $segment->setTitle($title);
        $segment->setParent($parent);

        foreach ($products as $product) {
            $segment->addProduct($product);
        }

        $this->manager->persist($segment);

        return $segment;
    }

    /**
     * Create a SimpleProduct entity
     * @param string $name        Name of the product
     * @param string $description Description of the product
     *
     * @return SimpleProduct
     */
    protected function createProduct($name, $description)
    {
        $product= new SimpleProduct();
        $product->setName($name);
        $product->setDescription($description);


        $this->manager->persist($product);

        return $product;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}

<?php
namespace Acme\Bundle\DemoSegmentationTreeBundle\DataFixtures\ORM\SimpleCustomerSegment;

use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleCustomerSegment;
use Acme\Bundle\DemoSegmentationTreeBundle\Entity\SimpleCustomer;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Load simple customer segment fixtures
 *
 * Execute with "php app/console doctrine:fixtures:load"
 *
 * @author Benoit Jacquemont <benoit@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 */
class LoadSimpleCustomerSegmentData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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

        $prospects = $this->createSegment('Prospects');

        $cust1 = $this->createCustomer('Mr Foo','555-12345');
        $cust2 = $this->createCustomer('Mr Bar','555-56788');
        $cust3 = $this->createCustomer('Ms Toto','555-3330');
        $custs1 = array($cust1,$cust2,$cust3);

        $potentialCustomers = $this->createSegment('Potential customers', $prospects, $custs1);

        $customers = $this->createSegment('Customers');

        $this->manager->flush();
    }

    /**
     * Create a Segment entity
     * @param string $title Title of the segment
     * @param SimpleCustomerSegment $parent parent segment
     * @param array $customers Customers that should be associated to this segment
     *
     * @return SimpleCustomerSegment
     */
    protected function createSegment($title, $parent = null, $customers = array())
    {
        $segment = new SimpleCustomerSegment();
        $segment->setTitle($title);
        $segment->setParent($parent);

        foreach($customers as $customer) {
            $segment->addCustomer($customer);
        }

        $this->manager->persist($segment);

        return $segment;
    }

    /**
     * Create a SimpleCustomer entity
     * @param string $name Name of the customer
     * @param string $phone phone of the customer
     *
     * @return SimpleCustomer
     */
    protected function createCustomer($name, $phone)
    {
        $customer= new SimpleCustomer();
        $customer->setName($name);
        $customer->setPhone($phone);


        $this->manager->persist($customer);

        return $customer;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}

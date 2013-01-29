<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller\KernelAwareControllerTest;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerControllerTest extends KernelAwareControllerTest
{

    /**
     * Define customer controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'customer';

    /**
     * List of locales to test
     * @staticvar multitype:string
     */
    protected static $locales = array('en', 'fr');

    /**
     * {@inheritdoc}
     */
    protected function getFixturesToLoad()
    {
        return array(
            'src/Acme/Bundle/DemoFlexibleEntityBundle/DataFixtures/ORM/Customer'
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getTablesToTruncate()
    {
        return array(
            'acmedemoflexibleentity_customer',
            'acmedemoflexibleentity_customer_value',
            'acmedemoflexibleentity_customer_value_option',
            'oroflexibleentity_attribute',
            'oroflexibleentity_attribute_option',
            'oroflexibleentity_attribute_option_value'
        );
    }

    /**
     * Get customer manager
     *
     * @return Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleEntityManager
     */
    protected function getCustomerManager()
    {
        return $this->getContainer()->get('customer_manager');
    }

    /**
     * Test related method
     */
    public function testIndexAction()
    {
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'index'));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     *
     * @throws \Exception
     */
    public function testShowAction()
    {
        // find customer to show
        $customer = $this->getCustomerManager()->getEntityRepository()->findOneBy(array());
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        // call and assert view
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl('en', 'show/'.$customer->getId()));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related action
     *
     * @throws \Exception
     */
    public function testRemoveAction()
    {
        // find customer to delete
        $customer = $this->getCustomerManager()->getEntityRepository()->findOneBy(array());
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        // count all customers in database
        $countCustomers = $this->countCustomers();

        // call and assert view
        $this->client->request('GET', self::prepareUrl('en', 'remove/'. $customer->getId()));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($countCustomers-1, $this->countCustomers());
    }

    /**
     * Test related method
     */
    public function testCreateAction()
    {
        // count customers in database
        $countCustomers = $this->countCustomers();

        // just call view to show form
        $this->client->request('GET', self::prepareUrl('en', 'create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // post data to create a customer entity
        $postData = array(
            'firstname' => 'Test',
            'lastname' => 'Test',
            'email' => 'mail@mail.com'
        );
        $this->client->request('POST', self::prepareUrl('en', 'create'), $postData);
        var_dump($this->client->getResponse()->getContent());
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    /**
     * Test related method
     */
//     public function testEditAction()
//     {
//         // count customers in database
//     }

    /**
     * Count customers in database
     *
     * @return integer
     */
    protected function countCustomers()
    {
        $customers = $this->getCustomerManager()->getEntityRepository()->findAll();

        return count($customers);
    }

//     /**
//      * Test query actions
//      */
//     public function testQueries()
//     {
//         $actions = array(
//             'querylazyload',
//             'queryonlydob',
//             'queryonlydobandgender',
//             'queryfilterfirstname',
//             'queryfilterfirstnameandcompany',
//             'queryfilterfirstnameandlimit',
//             'queryfilterfirstnameandorderbirthdatedesc'
//         );

//         foreach ($actions as $action) {
//             $this->client->request('GET', self::prepareUrl('en', $action));
//             $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//         }
//     }

//     /**
//      * Test query actions without data
//      */
//     public function testQueriesWithoutData()
//     {
//         // actions returning code 200
//         $actions = array(
//             'querylazyload',
//             'queryfilterfirstname',
//             'queryfilterfirstnameandlimit',
//         );
//         foreach ($actions as $action) {
//             $this->client->request('GET', self::prepareUrl('en', $action));
//             $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
//         }

//         // actions returning exception
//         $actions = array(
//             'queryonlydob',
//             'queryonlydobandgender',
//             'queryfilterfirstnameandcompany',
//             'queryfilterfirstnameandorderbirthdatedesc'
//         );
//         foreach ($actions as $action) {
//             $this->client->request('GET', self::prepareUrl('en', $action));
//             $this->assertEquals(500, $this->client->getResponse()->getStatusCode());
//         }
//     }

}

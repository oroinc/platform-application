<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

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
     * {@inheritdoc}
     */
    protected function getFixturesToLoad()
    {
        return array(
            'src/Acme/Bundle/DemoFlexibleEntityBundle/DataFixtures/ORM/Customer'
        );
    }

    /**
     * Get customer manager
     *
     * @return Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
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
        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'create'));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     */
    public function testEditAction()
    {
        // find customer to edit
        $customer = $this->getCustomerManager()->getEntityRepository()->findOneBy(array());
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'edit/'. $customer->getId()));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

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

    /**
     * Test related method
     */
    public function testQueryLazyLoadAction()
    {
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'query-lazy-load'));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Related method
     */
    public function testQueryAction()
    {
        // test with all parameters to null
        $params = array(
            'attributes' => 'null',
            'criteria'   => 'null',
            'orderBy'    => 'null',
            'limit'      => 'null',
            'offset'     => 'null'
        );
        $this->callQueryActionUrl($params);

        // test with only dob
        $params = array(
            'attributes' => 'dob'
        );
        $this->callQueryActionUrl($params);

        // test with dob and gender
        $params = array(
            'attributes' => 'dob&gender'
        );
        $this->callQueryActionUrl($params);

        // test filtered by firstname
        $params = array(
            'attributes' => 'null',
            'criteria'   => 'firstname=Nicolas'
        );
        $this->callQueryActionUrl($params);

        // test filtered by firstname and company
//         $params = array(
//             'criteria'   => 'firstname=Romain&company=Akeneo'
//         );
//         $this->callQueryActionUrl($params);

        // test dob, company and gender filtered by firstname and company
        $params = array(
            'attributes' => 'dob&company&gender',
            'criteria'   => 'firstname=Romain&company=Akeneo'
        );
        $this->callQueryActionUrl($params);

        // test filtered by firstname and limit
        $params = array(
            'criteria' => 'firstname=Nicolas',
            'limit'    => 10,
            'offset'   => 0
        );
        $this->callQueryActionUrl($params);

        // test select dob filtered by firstname and order by dob desc
        $params = array(
            'attributes' => 'dob',
            'criteria'   => 'firstname=Romain',
            'orderBy'    => 'dob=desc'
        );
        $this->callQueryActionUrl($params);
    }

    /**
     * Call query action and assert result
     * @param multitype $params
     */
    protected function callQueryActionUrl($params)
    {
        $attributes = (isset($params['attributes'])) ? $params['attributes'] : 'null';
        $criteria   = (isset($params['criteria'])) ? $params['criteria'] : 'null';
        $orderBy    = (isset($params['orderBy'])) ? $params['orderBy'] : 'null';
        $limit      = (isset($params['limit'])) ? $params['limit'] : 'null';
        $offset     = (isset($params['offset'])) ? $params['offset'] : 'null';

        $urlSuffix = $this->prepareUrlForQueryAction($attributes, $criteria, $orderBy, $limit, $offset);
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'query/'. $urlSuffix));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Prepare tested url with parameters
     *
     * @param string $attributes attribute codes
     * @param string $criteria   criterias
     * @param string $orderBy    order by
     * @param int    $limit      limit
     * @param int    $offset     offset
     *
     * @return string
     */
    protected function prepareUrlForQueryAction($attributes, $criteria, $orderBy, $limit, $offset)
    {
        return $attributes .'/'. $criteria .'/'. $orderBy .'/'. $limit .'/'. $offset;
    }

}

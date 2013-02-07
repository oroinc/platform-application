<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Controller;

use Acme\Bundle\DemoFlexibleEntityBundle\DataFixtures\ORM\Customer\LoadCustomerData;

/**
 * Test related class
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerAttributeControllerTest extends KernelAwareControllerTest
{

    /**
     * Define customer controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'customerattribute';

    /**
     * {@inheritdoc}
     */
    protected function getFixturesToLoad()
    {
        return array(
            new LoadCustomerData()
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
     * Test related action
     *
     * @throws \Exception
     */
    public function testRemoveAction()
    {
        // find customer to delete
        $customerAttribute = $this->getCustomerManager()->getAttributeRepository()->findOneBy(array());
        if (!$customerAttribute) {
            throw new \Exception('Customer not found');
        }

        // count all customers in database
        $countCustomerAttributes = $this->countCustomerAttributes();

        // call and assert view
        $this->client->request('GET', self::prepareUrl('en', 'remove/'. $customerAttribute->getId()));
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
        $this->assertEquals($countCustomerAttributes-1, $this->countCustomerAttributes());
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
        // find one to edit
        $attribute = $this->getCustomerManager()->getAttributeRepository()->findOneBy(array('entityType' => 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer'));
        if (!$attribute) {
            throw new \Exception('Customer not found');
        }

        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'edit/'. $attribute->getId()));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Count customer attributes in database
     *
     * @return integer
     */
    protected function countCustomerAttributes()
    {
        $attributes = $this->getCustomerManager()->getAttributeRepository()->findBy(array('entityType' => 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer'));

        return count($attributes);
    }

}

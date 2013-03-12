<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Controller;

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
     * Get customer manager
     *
     * @return \Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
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
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'index'),
                array(),
                array(),
                array('PHP_AUTH_USER' =>  self::AUTH_USER, 'PHP_AUTH_PW' => self::AUTH_PW)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     */
    public function testCreateAction()
    {
        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'create'),
                array(),
                array(),
                array('PHP_AUTH_USER' =>  self::AUTH_USER, 'PHP_AUTH_PW' => self::AUTH_PW)
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }

    /**
     * Test related method
     */
    public function testEditAction()
    {
        // find one to edit
        $attribute = $this->getCustomerManager()->getAttributeRepository()->findOneBy(
            array('entityType' => 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer')
        );
        if (!$attribute) {
            throw new \Exception('Customer not found');
        }

        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'edit/'. $attribute->getId()),
                array(),
                array(),
                array('PHP_AUTH_USER' =>  self::AUTH_USER, 'PHP_AUTH_PW' => self::AUTH_PW)
            );
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
        $attributes = $this->getCustomerManager()->getAttributeRepository()->findBy(
            array('entityType' => 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer')
        );

        return count($attributes);
    }
}

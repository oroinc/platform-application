<?php

namespace Acme\Bundle\DemoFlexibleEntityBundle\Tests\Functional\Controller;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class ProductControllerTest extends KernelAwareControllerTest
{

    /**
     * Define product controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'product';

    /**
     * Get product manager
     *
     * @return Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
     */
    protected function getProductManager()
    {
        return $this->getContainer()->get('product_manager');
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
        // find one to show
        $entity = $this->getProductManager()->getFlexibleRepository()->findOneBy(array());
        if (!$entity) {
            throw new \Exception('Customer not found');
        }

        // call and assert view
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl('en', 'show/'.$entity->getId()));
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
        $entity = $this->getProductManager()->getFlexibleRepository()->findOneBy(array());
        if (!$entity) {
            throw new \Exception('Customer not found');
        }

        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request('GET', self::prepareUrl($locale, 'edit/'. $entity->getId()));
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }
}

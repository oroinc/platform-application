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
class ProductAttributeControllerTest extends KernelAwareControllerTest
{

    /**
     * Define customer controller name for url generation
     * @staticvar string
     */
    protected static $controller = 'productattribute';

    /**
     * Get manager
     *
     * @return \Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager
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
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'index')
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
                self::prepareUrl($locale, 'create')
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
        $attribute = $this->getProductManager()->getAttributeRepository()->findOneBy(
            array('entityType' => 'Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product')
        );
        if (!$attribute) {
            throw new \Exception('Not found');
        }

        // just call view to show form
        foreach (self::$locales as $locale) {
            $this->client->request(
                'GET',
                self::prepareUrl($locale, 'edit/'. $attribute->getId())
            );
            $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        }
    }
}

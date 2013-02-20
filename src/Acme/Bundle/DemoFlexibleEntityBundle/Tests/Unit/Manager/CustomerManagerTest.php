<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Test\Unit\Manager;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleRadioType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\DateType;

use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Oro\Bundle\FlexibleEntityBundle\Entity\AttributeOption;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\CustomerValue;

use Acme\Bundle\DemoFlexibleEntityBundle\Entity\Customer;

use Acme\Bundle\DemoFlexibleEntityBundle\Tests\Unit\KernelAwareTest;

/**
 * Test related class
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerManagerTest extends KernelAwareTest
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * @staticvar integer
     */
    protected static $customerCount = 0;

    /**
     * List ofnom  customers
     * @var multitype
     */
    protected $customerList = array();

    /**
     * @var EntityAttributeValue
     */
    protected $attCompany;

    /**
     * @var EntityAttributeValue
     */
    protected $attDob;

    /**
     * @var EntityAttributeValue
     */
    protected $attGender;

    /**
     * Option gender
     * @var AttributeOption
     */
    protected $option;

    /**
     * UT set up
     */
    public function setUp()
    {
        parent::setUp();
        $this->manager = $this->container->get('customer_manager');
    }

    /**
     * Test related method
     */
    public function testCreateEntity()
    {
        $newCustomer = $this->manager->createFlexible();
        $this->assertTrue($newCustomer instanceof Customer);
        $newCustomer->setFirstname('Nicolas');
        $newCustomer->setLastname('Dupont');
        $this->assertEquals($newCustomer->getFirstname(), 'Nicolas');
    }
}

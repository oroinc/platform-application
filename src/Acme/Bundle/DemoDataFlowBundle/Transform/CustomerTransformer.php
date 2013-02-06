<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform;

use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;

use Oro\Bundle\FlexibleEntityBundle\Entity\Mapping\AbstractEntityFlexibleValue;

use Acme\Bundle\DemoDataFlowBundle\Model\Mapping\MagentoCustomerMapping;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Transform a Magento customer to BAP customer
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class CustomerTransformer implements DataTransformerInterface
{

    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * Constructor
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * {@inheritdoc}
     */
    public function transform($datas)
    {
        $customer = $this->manager->createFlexible();

        // convert without mapper
        if (isset($datas['email'])) {
            $customer->setEmail($datas['email']);
        }
        if (isset($datas['firstname'])) {
            $customer->setFirstname($datas['firstname']);
        }
        if (isset($datas['lastname'])) {
            $customer->setLastname($datas['lastname']);
        }

        // convert with mapper
        $mapper = new MagentoCustomerMapping();
        foreach ($mapper->getFields() as $field) {
            if (isset($datas[$field->getSource()])) {
                $flexibleValue = $customer->getValue($field->getDestination());
                $flexibleValue->setData($datas[$field->getSource()]);
            }
        }

        return $customer;
    }


    /**
     * {@inheritdoc}
     */
    public function reverseTransform($customer)
    {
        return null;
    }
}
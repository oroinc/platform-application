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
    public function transform($data)
    {
        $customer = $this->manager->createFlexible();

        // convert without mapper
        if (isset($data['email'])) {
            $customer->setEmail($data['email']);
        }
        if (isset($data['firstname'])) {
            $customer->setFirstname($data['firstname']);
        }
        if (isset($data['lastname'])) {
            $customer->setLastname($data['lastname']);
        }

        // convert with mapper
        $mapper = new MagentoCustomerMapping();
        foreach ($mapper->getFields() as $field) {
            if (isset($data[$field->getSource()])) {
                $flexibleValue = $customer->getValue($field->getDestination());
                $flexibleValue->setData($data[$field->getSource()]);
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
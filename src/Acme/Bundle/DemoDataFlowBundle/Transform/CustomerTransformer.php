<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Acme\Bundle\DemoDataFlowBundle\Transform\Mapping\CustomerMapping;
use Symfony\Component\Form\Exception\TransformationFailedException;

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
        $mapper = new CustomerMapping();
        foreach ($mapper->getFields() as $field) {
            if (isset($data[$field->getSource()])) {
                $flexibleValue = $customer->getValue($field->getDestination());
                if ($flexibleValue) {
                    $flexibleValue->setData($data[$field->getSource()]);
                } else {
                    $attributes = $this->manager->getFlexibleRepository()->getCodeToAttributes(
                        array($field->getDestination())
                    );
                    if (isset($attributes[$field->getDestination()])) {
                        $flexibleValue = $this->manager->createFlexibleValue();
                        $flexibleValue->setAttribute($attributes[$field->getDestination()]);
                        $flexibleValue->setData($data[$field->getSource()]);
                        $customer->addValue($flexibleValue);
                    }
                }
            }
        }

        return $customer;
    }


    /**
     * {@inheritdoc}
     */
    public function reverseTransform($customer)
    {
        throw new TransformationFailedException('This transformation is not implemented');
    }
}

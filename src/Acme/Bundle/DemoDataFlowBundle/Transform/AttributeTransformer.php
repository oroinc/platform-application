<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform;

use Symfony\Component\Form\DataTransformerInterface;
use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleSelectType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\DateType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextAreaType;

/**
 * Transform a Magento attribute to BAP attribute
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class AttributeTransformer implements DataTransformerInterface
{
    /**
     * @var FlexibleManager
     */
    protected $manager;

    /**
     * @param FlexibleManager $manager
     */
    public function __construct(FlexibleManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Transforms a oro attribute to a magento attribute
     *
     * @param Attribute $attribute
     *
     * @return array
     */
    public function transform($attribute)
    {
        throw new TransformationFailedException('This transformation is not implemented');
    }

    /**
     * Transforms a magento attribute to a oro attribute
     *
     * @param array $magentoAttribute
     *
     * @return Attribute $attribute
     * @throws TransformationFailedException if object is not found.
     */
    public function reverseTransform($magentoAttribute)
    {
        if (!$magentoAttribute) {
            return null;
        }

        // mapping
        $magentoCode = $magentoAttribute['attribute_code'];
        $magentoType = $magentoAttribute['frontend_input'];
        $type = null;
        switch ($magentoType) {
            case 'price':    $type = new MoneyType();
                break;
            case 'select':   $type = new OptionSimpleSelectType();
                break;
            case 'date':     $type = new DateType();
                break;
            case 'textarea': $type = new TextAreaType();
                break;
            default:         $type = new TextType();
                break;
        }

        // create attribute
        $attribute = $this->manager->createAttribute($type);
        $attribute->setCode($magentoCode);

        return $attribute;
    }
}

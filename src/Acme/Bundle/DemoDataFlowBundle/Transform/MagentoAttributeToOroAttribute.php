<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleSelectType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleRadioType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionMultiSelectType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionMultiCheckboxType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\DateType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MetricType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextAreaType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\UrlType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\NumberType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MailType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\IntegerType;


/**
 * Transform a Magento attribute to BAP attribute
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
 *
 */
class MagentoAttributeToOroAttribute implements DataTransformerInterface
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
     * @param mixed $attribute
     *
     * @return array
     */
    public function transform($attribute)
    {
        // TODO
        return null;
    }

    /**
     * Transforms a magento attribute to a oro attribute
     *
     * @param mixed $magentoAttribute
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
            case 'price':    $type = new MoneyType(); break;
            case 'select':   $type = new OptionSimpleSelectType(); break;
            case 'date':     $type = new DateType(); break;
            case 'textarea': $type = new TextAreaType(); break;
            default: $type = new TextType(); break;
        }

        // create attribute
        $attribute = $this->manager->createAttribute($type);
        $attribute->setCode($magentoCode);

        return $attribute;
    }
}
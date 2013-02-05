<?php
namespace Acme\Bundle\DemoDataFlowBundle\Transform;

use Oro\Bundle\FlexibleEntityBundle\Manager\FlexibleManager;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Entity\Attribute;


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

        $magentoCode = $magentoAttribute['attribute_code'];
        $attribute = $this->manager->getStorageManager()
            ->getRepository('OroFlexibleEntityBundle:Attribute')
            ->findOneBy(array('code' => $magentoCode));

        // not exists
        if (null === $attribute) {

            $attribute = $this->manager->createAttribute(new TextType());
            $attribute->setCode($magentoCode);

            /*
            throw new TransformationFailedException(sprintf(
                'Attribute "%s" is not found!',
                $magentoAttribute['attribute_code']
            ));*/
        }

        return $attribute;
    }
}
<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\DataFixtures\ORM\Product;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;
use Acme\Bundle\DemoFlexibleEntityBundle\Entity\ProductAttribute;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MultiOptionsType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MetricType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextAreaType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;

/**
* Load products
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadProductData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Flexible entity manager
     * @var FlexibleEntityManager
     */
    protected $manager;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get product manager
     * @return FlexibleEntityManager
     */
    protected function getProductManager()
    {
        return $this->container->get('product_manager');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadAttributes();
        $this->loadProducts();
        $this->loadTranslations();

    }

    /**
     * Load attributes
     *
     * @return array
     */
    public function loadAttributes()
    {
        $messages = array();

        // force in english
        $this->getProductManager()->setLocale('en_US');

        // attribute name (if not exists)
        $attributeCode = 'name';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createEntityAttribute(new TextType());
        $productAttribute->setName('Name');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);
        $messages[]= "Attribute ".$attributeCode." has been created";

        // attribute price (if not exists)
        $attributeCode = 'price';
        $productAttribute = $this->getProductManager()->createEntityAttribute(new MoneyType());
        $productAttribute->setName('Price');
        $productAttribute->setCode($attributeCode);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);
        $messages[]= "Attribute ".$attributeCode." has been created";

        // attribute description (if not exists)
        $attributeCode = 'description';
        $productAttribute = $this->getProductManager()->createEntityAttribute(new TextAreaType());
        $productAttribute->setName('Description');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(true);
        $productAttribute->setScopable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);
        $messages[]= "Attribute ".$attributeCode." has been created";

        // attribute size (if not exists)
        $attributeCode= 'size';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createEntityAttribute(new MetricType());
        $productAttribute->setName('Size');
        $productAttribute->setCode($attributeCode);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);
        $messages[]= "Attribute ".$attributeCode." has been created";

        // attribute color (if not exists)
        $attributeCode= 'color';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createEntityAttribute(new MultiOptionsType());
        $productAttribute->setName('Color');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(false); // only one value but option can be translated in option values
        // add translatable option and related value "Red", "Blue", "Green"
        $colors = array("Red", "Blue", "Green");
        foreach ($colors as $color) {
            $option = $this->getProductManager()->createAttributeOption();
            $option->setTranslatable(true);
            $optionValue = $this->getProductManager()->createAttributeOptionValue();
            $optionValue->setValue($color);
            $option->addOptionValue($optionValue);
            $productAttribute->addOption($option);
        }
        $this->getProductManager()->getStorageManager()->persist($productAttribute);
        $messages[]= "Attribute ".$attributeCode." has been created";

        $this->getProductManager()->getStorageManager()->flush();

        return $messages;
    }

    /**
     * Load products
     *
     * @return array
     */
    public function loadProducts()
    {
        $messages = array();

        // force in english because product is translatable
        $this->getProductManager()->setLocale('en_US');

        // get attributes
        $attName = $this->getProductManager()->getEntityRepository()->findAttributeByCode('name');
        $attDescription = $this->getProductManager()->getEntityRepository()->findAttributeByCode('description');
        $attSize = $this->getProductManager()->getEntityRepository()->findAttributeByCode('size');
        $attColor = $this->getProductManager()->getEntityRepository()->findAttributeByCode('color');
        $attPrice = $this->getProductManager()->getEntityRepository()->findAttributeByCode('price');
        // get attribute color options
        $optColors = $this->getProductManager()->getAttributeOptionRepository()->findBy(array('attribute' => $attColor));
        $colors = array();
        foreach ($optColors as $option) {
            $colors[]= $option;
        }

        $indSku = 1;
        $descriptions = array('my long description', 'my other description');
        for ($ind= 1; $ind <= 3; $ind++) {

            // add product with only sku and name
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->createEntity();
            $newProduct->setSku($prodSku);
            if ($attName) {
                $valueName = $this->getProductManager()->createEntityValue();
                $valueName->setAttribute($attName);
                $valueName->setData('my name '.$indSku);
                $newProduct->addValue($valueName);
            }
            $messages[]= "Product ".$prodSku." has been created";
            $this->getProductManager()->getStorageManager()->persist($newProduct);
            $indSku++;

            // add product with sku, name, description, color and size
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->createEntity();
            $newProduct->setSku($prodSku);
            if ($attName) {
                $valueName = $this->getProductManager()->createEntityValue();
                $valueName->setAttribute($attName);
                $valueName->setData('my name '.$indSku);
                $newProduct->addValue($valueName);
            }
            if ($attDescription) {
                // scope ecommerce
                $value = $this->getProductManager()->createEntityValue();
                $value->setScope(ProductAttribute::SCOPE_ECOMMERCE);
                $value->setAttribute($attDescription);
                $myDescription = $descriptions[$ind%2];
                $value->setData($myDescription.'(ecommerce)');
                $newProduct->addValue($value);
                // scope mobile
                $value = $this->getProductManager()->createEntityValue();
                $value->setScope(ProductAttribute::SCOPE_MOBILE);
                $value->setAttribute($attDescription);
                $value->setData($myDescription.'(mobile)');
                $newProduct->addValue($value);
            }
            if ($attSize) {
                $valueSize = $this->getProductManager()->createEntityValue();
                $valueSize->setAttribute($attSize);
                $valueSize->setData(175); // single value
                $valueSize->setUnit('mm');
                $newProduct->addValue($valueSize);
            }
            if ($attColor) {
                $value = $this->getProductManager()->createEntityValue();
                $value->setAttribute($attColor);
                // pick many colors (multiselect)
                $firstColorOpt = $colors[rand(0, count($colors)-1)];
                $value->addOption($firstColorOpt);
                $secondColorOpt = $colors[rand(0, count($colors)-1)];
                if ($firstColorOpt->getId() != $secondColorOpt->getId()) {
                    $value->addOption($secondColorOpt);
                }
                $newProduct->addValue($value);
            }
            $this->getProductManager()->getStorageManager()->persist($newProduct);
            $messages[]= "Product ".$prodSku." has been created";
            $indSku++;

            // add product with sku, name, size and price
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->createEntity();
            $newProduct->setSku($prodSku);
            if ($attName) {
                $valueName = $this->getProductManager()->createEntityValue();
                $valueName->setAttribute($attName);
                $valueName->setData('my name '.$indSku);
                $newProduct->addValue($valueName);
            }
            if ($attSize) {
                $valueSize = $this->getProductManager()->createEntityValue();
                $valueSize->setAttribute($attSize);
                $valueSize->setData(175);
                $valueSize->setUnit('mm');
                $newProduct->addValue($valueSize);
            }
            if ($attPrice) {
                $valuePrice = $this->getProductManager()->createEntityValue();
                $valuePrice->setAttribute($attPrice);
                $valuePrice->setData(rand(5, 100));
                $valuePrice->setCurrency('USD');
                $newProduct->addValue($valuePrice);
            }
            $this->getProductManager()->getStorageManager()->persist($newProduct);
            $messages[]= "Product ".$prodSku." has been created";
            $indSku++;
        }

        $this->getProductManager()->getStorageManager()->flush();

        return $messages;
    }

    /**
     * Load translated data
     *
     * @return array
     */
    public function loadTranslations()
    {
        // get attributes
        $attName = $this->getProductManager()->getEntityRepository()->findAttributeByCode('name');
        $attDescription = $this->getProductManager()->getEntityRepository()->findAttributeByCode('description');

        // get products
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes();
        $ind = 1;
        foreach ($products as $product) {
            // translate name value
            if ($attName) {
                if ($product->setLocale('en_US')->getValue('name') != null) {
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setAttribute($attName);
                    $value->setLocale('fr_FR');
                    $value->setData('mon nom FR '.$ind);
                    $product->addValue($value);
                    $this->getProductManager()->getStorageManager()->persist($value);
                    $messages[]= "Value 'name' has been translated";
                }
            }
            // translate description value
            if ($attDescription) {
                // check if a value en_US + scope ecommerce exists
                if ($product->setLocale('en_US')->setScope('ecommerce')->getValue('description') != null) {
                    // scope ecommerce
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setLocale('fr_FR');
                    $value->setScope(ProductAttribute::SCOPE_ECOMMERCE);
                    $value->setAttribute($attDescription);
                    $value->setData('ma description FR (ecommerce) '.$ind);
                    $product->addValue($value);
                    $this->getProductManager()->getStorageManager()->persist($value);
                    // scope mobile
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setLocale('fr_FR');
                    $value->setScope(ProductAttribute::SCOPE_MOBILE);
                    $value->setAttribute($attDescription);
                    $value->setData('ma description FR (mobile) '.$ind);
                    $product->addValue($value);
                    $this->getProductManager()->getStorageManager()->persist($value);
                    $messages[]= "Value 'description' has been translated";
                }
            }
            $ind++;
        }

        // get color attribute options
        $attColor = $this->getProductManager()->getEntityRepository()->findAttributeByCode('color');
        $colors = array("Red" => "Rouge", "Blue" => "Bleu", "Green" => "Vert");
        // translate
        foreach ($colors as $colorEn => $colorFr) {
            $optValueEn = $this->getProductManager()->getAttributeOptionValueRepository()->findOneBy(array('value' => $colorEn));
            $optValueFr = $this->getProductManager()->getAttributeOptionValueRepository()->findOneBy(array('value' => $colorFr));
            if ($optValueEn and !$optValueFr) {
                $option = $optValueEn->getOption();
                $optValueFr = $this->getProductManager()->createAttributeOptionValue();
                $optValueFr->setValue($colorFr);
                $optValueFr->setLocale('fr_FR');
                $option->addOptionValue($optValueFr);
                $this->getProductManager()->getStorageManager()->persist($optValueFr);
                $messages[]= "Option '".$colorEn."' has been translated";
            }
        }

        $this->getProductManager()->getStorageManager()->flush();

        return $messages;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }

    /**
     * Generate firstname
     * @return string
     */
    protected function generateFirstname()
    {
        $listFirstname = array('Nicolas', 'Romain');
        $random = rand(0, count($listFirstname)-1);

        return $listFirstname[$random];
    }

    /**
     * Generate lastname
     * @return string
     */
    protected function generateLastname()
    {
        $listLastname = array('Dupont', 'Monceau');
        $random = rand(0, count($listLastname)-1);

        return $listLastname[$random];
    }

    /**
     * Generate birthdate
     * @return string
     */
    protected function generateBirthDate()
    {
        $year  = rand(1980, 2000);
        $month = rand(1, 12);
        $day   = rand(1, 28);

        return $year .'-'. $month .'-'. $day;
    }
}
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
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionMultiCheckboxType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MetricType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextAreaType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;

/**
 * Load products
 *
 * Execute with "php app/console doctrine:fixtures:load"
 *
 * @author Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license http://opensource.org/licenses/MIT MIT
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
     * @var FlexibleManager
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
     * @return FlexibleManager
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
    }

    /**
     * Load attributes
     *
     * @return array
     */
    public function loadAttributes()
    {
        // attribute name
        $attributeCode = 'name';
        $attribute = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createAttributeExtended(new TextType());
        $productAttribute->setName('Name');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(true);
        $productAttribute->setRequired(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute price
        $attributeCode = 'price';
        $productAttribute = $this->getProductManager()->createAttributeExtended(new MoneyType());
        $productAttribute->setName('Price');
        $productAttribute->setCode($attributeCode);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute description
        $attributeCode = 'description';
        $productAttribute = $this->getProductManager()->createAttributeExtended(new TextAreaType());
        $productAttribute->setName('Description');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(true);
        $productAttribute->setScopable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute size
        $attributeCode= 'size';
        $attribute = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createAttributeExtended(new MetricType());
        $productAttribute->setName('Size');
        $productAttribute->setCode($attributeCode);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute color and translated options
        $attributeCode= 'color';
        $attribute = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode($attributeCode);
        $productAttribute = $this->getProductManager()->createAttributeExtended(new OptionMultiCheckboxType());
        $productAttribute->setName('Color');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setTranslatable(false); // only one value but option can be translated in option values
        $colors = array(
            array('en_US' => 'Red', 'fr_FR' => 'Rouge', 'de_DE' => 'Rot'),
            array('en_US' => 'Blue', 'fr_FR' => 'Bleu', 'de_DE' => 'Blau'),
            array('en_US' => 'Green', 'fr_FR' => 'Vert', 'de_DE' => 'Grün'),
            array('en_US' => 'Purple', 'fr_FR' => 'Violet', 'de_DE' => 'Lila'),
            array('en_US' => 'Orange', 'fr_FR' => 'Orange', 'de_DE' => 'Orange'),
        );
        foreach ($colors as $color) {
            $option = $this->getProductManager()->createAttributeOption();
            $option->setTranslatable(true);
            $productAttribute->addOption($option);
            foreach ($color as $locale => $translated) {
                $optionValue = $this->getProductManager()->createAttributeOptionValue();
                $optionValue->setValue($translated);
                $optionValue->setLocale($locale);
                $option->addOptionValue($optionValue);
            }
        }
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        $this->getProductManager()->getStorageManager()->flush();
    }

    /**
     * Load products
     *
     * @return array
     */
    public function loadProducts()
    {
        $nbProducts = 25;
        $batchSize = 500;

        // get attributes
        $attName = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode('name');
        $attDescription = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode('description');
        $attSize = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode('size');
        $attColor = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode('color');
        $attPrice = $this->getProductManager()->getFlexibleRepository()->findAttributeByCode('price');
        // get attribute color options
        $optColors = $this->getProductManager()->getAttributeOptionRepository()->findBy(
            array('attribute' => $attColor)
        );
        $colors = array();
        foreach ($optColors as $option) {
            $colors[]= $option;
        }

        $descriptions = array('my long description', 'my other description');
        for ($ind= 0; $ind < $nbProducts; $ind++) {

            // sku
            $prodSku = 'sku-'.$ind;
            $product = $this->getProductManager()->createFlexible();
            $product->setSku($prodSku);

            // name
            $names = array('en_US' => 'my product name', 'fr_FR' => 'mon nom de produit', 'de_DE' => 'produkt namen');
            foreach ($names as $locale => $data) {
                $value = $this->getProductManager()->createFlexibleValue();
                $value->setAttribute($attName);
                $value->setLocale($locale);
                $value->setData($data.' '.$ind);
                $product->addValue($value);
            }

            // description
            $locales = array('en_US', 'fr_FR', 'de_DE');
            $scopes = array(ProductAttribute::SCOPE_ECOMMERCE, ProductAttribute::SCOPE_MOBILE);
            foreach ($locales as $locale) {
                foreach ($scopes as $scope) {
                    $value = $this->getProductManager()->createFlexibleValue();
                    $value->setLocale($locale);
                    $value->setScope($scope);
                    $value->setAttribute($attDescription);
                    $product->addValue($value);
                    $value->setData('description ('.$locale.') ('.$scope.') '.$ind);
                }
            }

            // size
            $value = $this->getProductManager()->createFlexibleValue();
            $value->setAttribute($attSize);
            $value->setData(rand(5, 10));
            $value->setUnit('mm');
            $product->addValue($value);

            // color
            $value = $this->getProductManager()->createFlexibleValue();
            $value->setAttribute($attColor);
            $firstColorOpt = $colors[rand(0, count($colors)-1)];
            $value->addOption($firstColorOpt);
            $secondColorOpt = $colors[rand(0, count($colors)-1)];
            if ($firstColorOpt->getId() != $secondColorOpt->getId()) {
                $value->addOption($secondColorOpt);
            }
            $product->addValue($value);

            // price
            $value = $this->getProductManager()->createFlexibleValue();
            $value->setAttribute($attPrice);
            $value->setData(rand(5, 100));
            $value->setCurrency('USD');
            $product->addValue($value);

            $this->getProductManager()->getStorageManager()->persist($product);

            if (($ind % $batchSize) == 0) {
                $this->getProductManager()->getStorageManager()->flush();
                // detaches all products and values from doctrine
                $this->getProductManager()->getStorageManager()->clear('Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product');
                $this->getProductManager()->getStorageManager()->clear('Acme\Bundle\DemoFlexibleEntityBundle\Entity\ProductValue');
            }
        }

        $this->getProductManager()->getStorageManager()->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 3;
    }
}
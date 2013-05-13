<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\DataFixtures\ORM\Product;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;
use Acme\Bundle\DemoFlexibleEntityBundle\Entity\ProductAttribute;
use Oro\Bundle\FlexibleEntityBundle\Entity\Metric;
use Oro\Bundle\FlexibleEntityBundle\Entity\Price;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionMultiCheckboxType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MetricType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextAreaType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\MoneyType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\PriceType;

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
    const DEFAULT_COUNTER_VALUE = 25;

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
     * Entities Counter
     * @var integer
     */
    protected $counter;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->setProductManager();
        //prepare entity counter
        $this->counter = self::DEFAULT_COUNTER_VALUE;
        if (isset($container->counter)) {
            $this->counter = $container->counter;
        } else {
            if ( $this->container->getParameter('kernel.environment') == 'perf'
                && $container->hasParameter('performance.products')) {
                $this->counter = $container->getParameter('performance.products');
            }
        }
    }

    /**
     * Set product manager
     */
    public function setProductManager()
    {
        $this->manager = $this->container->get('product_manager');
    }

    /**
     * Get product manager
     * @return FlexibleManager
     */
    protected function getProductManager()
    {
        return $this->manager;
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
        $productAttribute = $this->getProductManager()->createAttribute('oro_flexibleentity_text');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setLabel('Name');
        $productAttribute->setTranslatable(true);
        $productAttribute->setSearchable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute price
        $attributeCode = 'price';
        $productAttribute = $this->getProductManager()->createAttribute('oro_flexibleentity_price');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setLabel('Price');
        $productAttribute->setSearchable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute description
        $attributeCode = 'description';
        $productAttribute = $this->getProductManager()->createAttribute('oro_flexibleentity_textarea');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setLabel('Short description');
        $productAttribute->setTranslatable(true);
        $productAttribute->setScopable(true);
        $productAttribute->setSearchable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute size
        $attributeCode= 'size';
        $productAttribute = $this->getProductManager()->createAttribute('oro_flexibleentity_metric');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setLabel('Size');
        $productAttribute->setSearchable(true);
        $this->getProductManager()->getStorageManager()->persist($productAttribute);

        // attribute color and translated options
        $attributeCode= 'color';
        $productAttribute = $this->getProductManager()->createAttribute('oro_flexibleentity_multiselect');
        $productAttribute->setCode($attributeCode);
        $productAttribute->setLabel('Color');
        $productAttribute->setSearchable(true);
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
        $nbProducts = $this->counter;
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
            $scopes = array('ecommerce', 'mobile');
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
            $metric = new Metric();
            $metric->setUnit('mm');
            $metric->setData(rand(5, 10));
            $value->setData($metric);
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
            $price = new Price();
            $price->setData(rand(5, 10));
            $price->setCurrency('USD');
            $value->setData($price);
            $product->addValue($value);

            $this->getProductManager()->getStorageManager()->persist($product);

            if (($ind % $batchSize) == 0) {
                $this->getProductManager()->getStorageManager()->flush();
                // detaches all products and values from doctrine
                $this->getProductManager()
                    ->getStorageManager()
                    ->clear('Acme\Bundle\DemoFlexibleEntityBundle\Entity\Product');
                $this->getProductManager()
                    ->getStorageManager()
                    ->clear('Acme\Bundle\DemoFlexibleEntityBundle\Entity\ProductValue');
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

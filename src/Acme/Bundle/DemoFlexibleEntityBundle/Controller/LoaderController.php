<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\Controller;

use Oro\Bundle\FlexibleEntityBundle\Model\Attribute\Type\AbstractAttributeType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Loader controller.
 * Aims to create and insert data for entities
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 *
 * @Route("/loader")
 */
class LoaderController extends Controller
{

    /**
     * Insertion a list of product
     * @Route("/product")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productAction()
    {
        $messages = array();

        // force in english because product is translatable
        $this->getProductManager()->setLocaleCode('en_US');

        // get attributes
        $attName = $this->getProductManager()->getEntityRepository()->findAttributeByCode('name');
        $attDescription = $this->getProductManager()->getEntityRepository()->findAttributeByCode('description');
        $attSize = $this->getProductManager()->getEntityRepository()->findAttributeByCode('size');
        $attColor = $this->getProductManager()->getEntityRepository()->findAttributeByCode('color');
        $attPrice = $this->getProductManager()->getEntityRepository()->findAttributeByCode('price');
        // get first attribute option
        $optColor = $this->getProductManager()->getAttributeOptionRepository()->findOneBy(array('attribute' => $attColor));

        $indSku = 1;
        $descriptions = array('my long description', 'my other description');
        for ($ind= 1; $ind <= 33; $ind++) {

            // add product with only sku and name
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->getEntityRepository()->findOneBySku($prodSku);
            if ($newProduct) {
                $messages[]= "Product ".$prodSku." already exists";
            } else {
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
            }

            // add product with sku, name, description, color and size
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->getEntityRepository()->findOneBySku($prodSku);
            if ($newProduct) {
                $messages[]= "Product ".$prodSku." already exists";
            } else {
                $newProduct = $this->getProductManager()->createEntity();
                $newProduct->setSku($prodSku);
                if ($attName) {
                    $valueName = $this->getProductManager()->createEntityValue();
                    $valueName->setAttribute($attName);
                    $valueName->setData('my name '.$indSku);
                    $newProduct->addValue($valueName);
                }
                if ($attDescription) {
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setAttribute($attDescription);
                    $value->setData($descriptions[$ind%2]);
                    $newProduct->addValue($value);
                }
                if ($attSize) {
                    $valueSize = $this->getProductManager()->createEntityValue();
                    $valueSize->setAttribute($attSize);
                    $valueSize->setData(175);
                    $valueSize->setUnit('mm');
                    $newProduct->addValue($valueSize);
                }
                if ($attColor) {
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setAttribute($attColor);
                    $value->setData($optColor); // we set option as data, you can use $value->setOption($optColor) too
                    $newProduct->addValue($value);
                }
                $this->getProductManager()->getStorageManager()->persist($newProduct);
                $messages[]= "Product ".$prodSku." has been created";
                $indSku++;
            }

            // add product with sku, name, size and price
            $prodSku = 'sku-'.$indSku;
            $newProduct = $this->getProductManager()->getEntityRepository()->findOneBySku($prodSku);
            if ($newProduct) {
                $messages[]= "Product ".$prodSku." already exists";
            } else {
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
        }

        $this->getProductManager()->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', implode(', ', $messages));

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_product_index'));
    }

    /**
     * Translate products
     * @Route("/producttranslate")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productTranslateAction()
    {
        $messages = array();

        // force in english
        $this->getProductManager()->setLocaleCode('en_US');

        // get attributes
        $attName = $this->getProductManager()->getEntityRepository()->findAttributeByCode('name');
        $attDescription = $this->getProductManager()->getEntityRepository()->findAttributeByCode('description');

        // get products
        $products = $this->getProductManager()->getEntityRepository()->findByWithAttributes();
        $ind = 1;
        foreach ($products as $product) {
            // translate name value
            if ($attName) {
                if ($product->setLocaleCode('en_US')->getValue('name') != null) {
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setAttribute($attName);
                    $value->setLocaleCode('fr_FR');
                    $value->setData('mon nom FR '.$ind++);
                    $product->addValue($value);
                    $this->getProductManager()->getStorageManager()->persist($value);
                    $messages[]= "Value 'name' has been translated";
                }
            }
            // translate description value
            if ($attDescription) {
                if ($product->getValue('description') != null) {
                    $value = $this->getProductManager()->createEntityValue();
                    $value->setAttribute($attDescription);
                    $value->setLocaleCode('fr_FR');
                    $value->setData('ma description FR '.$ind++);
                    $product->addValue($value);
                    $this->getProductManager()->getStorageManager()->persist($value);
                    $messages[]= "Value 'description' has been translated";
                }
            }
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
                $optValueFr->setLocaleCode('fr_FR');
                $option->addOptionValue($optValueFr);
                $this->getProductManager()->getStorageManager()->persist($optValueFr);
                $messages[]= "Option '".$colorEn."' has been translated";
            }
        }

        $this->getProductManager()->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', implode(', ', $messages));

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_product_index'));
    }

    /**
     * Reset database with drop then create schema
     * @Route("/truncatedb")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function truncateDbAction()
    {
        // update schema / truncate db
        $em = $this->getProductManager()->getStorageManager();
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        if (!empty($metadatas)) {
            $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $tool->dropSchema($metadatas);
            $tool->createSchema($metadatas);
        }

        $this->get('session')->setFlash('notice', "DB has been truncated with success (schema re-generation)");

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_flexible_index'));
    }

    /**
     * Insert a list of manufacturer
     *
     * @Route("/manufacturer")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function manufacturerAction()
    {
        $names = array('Dell', 'Lenovo', 'Acer', 'Asus', 'HP');
        $mm = $this->getManufacturerManager();

        // new instances if not exist
        foreach ($names as $name) {
            $manufacturer = $mm->getEntityRepository()->findByName($name);
            if (!$manufacturer) {
                $manufacturer = $mm->createEntity();
                $manufacturer->setName($name);
                $mm->getStorageManager()->persist($manufacturer);
            }
        }

        // save
        $mm->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', 'Manufacturer has been inserted');

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_manufacturer_index'));
    }

    /**
     * Insert a list of customers
     * @Route("/customer")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerAction()
    {
        $messages = array();

        // get attributes
        $attCompany = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode('company');
        $attDob = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode('dob');
        $attGender = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode('gender');
        // get first attribute option
        $optGender = $this->getCustomerManager()->getAttributeOptionRepository()->findOneBy(array('attribute' => $attGender));

        for ($ind= 1; $ind < 100; $ind++) {

            // add customer with email, firstname, lastname, dob
            $custEmail = 'email-'.($ind++).'@mail.com';
            $customer = $this->getCustomerManager()->getEntityRepository()->findOneByEmail($custEmail);
            if ($customer) {
                $messages[]= "Customer ".$custEmail." already exists";
            } else {
                $customer = $this->getCustomerManager()->createEntity();
                $customer->setEmail($custEmail);
                $customer->setFirstname($this->generateFirstname());
                $customer->setLastname($this->generateLastname());
                // add dob value
                if ($attCompany) {
                    $value = $this->getCustomerManager()->createEntityValue();
                    $value->setAttribute($attDob);
                    $value->setData(new \DateTime($this->generateBirthDate()));
                    $customer->addValue($value);
                }
                $messages[]= "Customer ".$custEmail." has been created";
                $this->getCustomerManager()->getStorageManager()->persist($customer);
            }

            // add customer with email, firstname, lastname, company and gender
            $custEmail = 'email-'.($ind++).'@mail.com';
            $customer = $this->getCustomerManager()->getEntityRepository()->findOneByEmail($custEmail);
            if ($customer) {
                $messages[]= "Customer ".$custEmail." already exists";
            } else {
                $customer = $this->getCustomerManager()->createEntity();
                $customer->setEmail($custEmail);
                $customer->setFirstname($this->generateFirstname());
                $customer->setLastname($this->generateLastname());
                // add company value
                if ($attCompany) {
                    $value = $this->getCustomerManager()->createEntityValue();
                    $value->setAttribute($attCompany);
                    $value->setData('Akeneo');
                    $customer->addValue($value);
                }
                // add date of birth
                if ($attDob) {
                    $value = $this->getCustomerManager()->createEntityValue();
                    $value->setAttribute($attDob);
                    $value->setData(new \DateTime($this->generateBirthDate()));
                    $customer->addValue($value);
                }
                // add gender
                if ($attGender) {
                    $value = $this->getCustomerManager()->createEntityValue();
                    $value->setAttribute($attGender);
                    $value->setData($optGender);  // we set option as data, you can use $value->setOption($optGender) too
                    $customer->addValue($value);
                }
                $messages[]= "Customer ".$custEmail." has been created";
                $this->getCustomerManager()->getStorageManager()->persist($customer);
            }
        }

        $this->getCustomerManager()->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', implode(', ', $messages));

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_index'));
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

    /**
     * Insert customer attributes
     * @Route("/customerattribute")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function customerAttributeAction()
    {
        $messages = array();

        // force in english
        $this->getCustomerManager()->setLocaleCode('en_US');

        // attribute company (if not exists)
        $attCode = 'company';
        $att = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode($attCode);
        if ($att) {
            $messages[]= "Attribute ".$attCode." already exists";
        } else {
            $att = $this->getCustomerManager()->createAttribute();
            $att->setCode($attCode);
            $att->setBackendType(AbstractAttributeType::BACKEND_TYPE_VARCHAR);
            $this->getCustomerManager()->getStorageManager()->persist($att);
            $messages[]= "Attribute ".$attCode." has been created";
        }

        // attribute date of birth (if not exists)
        $attCode = 'dob';
        $att = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode($attCode);
        if ($att) {
            $messages[]= "Attribute ".$attCode." already exists";
        } else {
            $att = $this->getCustomerManager()->createAttribute();
            $att->setCode($attCode);
            $att->setBackendType(AbstractAttributeType::BACKEND_TYPE_DATE);
            $this->getCustomerManager()->getStorageManager()->persist($att);
            $messages[]= "Attribute ".$attCode." has been created";
        }

        // attribute gender (if not exists)
        $attCode = 'gender';
        $att = $this->getCustomerManager()->getEntityRepository()->findAttributeByCode($attCode);
        if ($att) {
            $messages[]= "Attribute ".$attCode." already exists";
        } else {
            $att = $this->getCustomerManager()->createAttribute();
            $att->setCode($attCode);
            $att->setBackendType(AbstractAttributeType::BACKEND_TYPE_OPTION);
            // add option and related value
            $opt = $this->getCustomerManager()->createAttributeOption();
            $optVal = $this->getCustomerManager()->createAttributeOptionValue();
            $optVal->setValue('Mr');
            $opt->addOptionValue($optVal);
            $att->addOption($opt);
            // add another option
            $opt = $this->getCustomerManager()->createAttributeOption();
            $optVal = $this->getCustomerManager()->createAttributeOptionValue();
            $optVal->setValue('Mrs');
            $opt->addOptionValue($optVal);
            $att->addOption($opt);
            $this->getCustomerManager()->getStorageManager()->persist($att);
            $messages[]= "Attribute ".$attCode." has been created";
        }

        $this->getCustomerManager()->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', implode(', ', $messages));

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_customer_attribute'));
    }

    /**
     * Insert product attributes
     * @Route("/productattribute")
     * @Template()
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function productAttributeAction()
    {
        $messages = array();

        // force in english
        $this->getProductManager()->setLocaleCode('en_US');

        // attribute name (if not exists)
        $attributeCode = 'name';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        if ($attribute) {
            $messages[]= "Attribute ".$attributeCode." already exists";
        } else {
            $productAttribute = $this->getProductManager()->createFlexibleAttribute();
            $productAttribute->setName('Name');
            $productAttribute->getAttribute()->setCode($attributeCode);
            $productAttribute->getAttribute()->setRequired(true);
            $productAttribute->getAttribute()->setBackendType(AbstractAttributeType::BACKEND_TYPE_VARCHAR);
            $productAttribute->getAttribute()->setTranslatable(true);
            $this->getProductManager()->getStorageManager()->persist($productAttribute);
            $messages[]= "Attribute ".$attributeCode." has been created";
        }

        // attribute price (if not exists)
        $attributeCode = 'price';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        if ($attribute) {
            $messages[]= "Attribute ".$attributeCode." already exists";
        } else {
            $productAttribute = $this->getProductManager()->createFlexibleAttribute();
            $productAttribute->setName('Price');
            $productAttribute->getAttribute()->setCode($attributeCode);
            $productAttribute->getAttribute()->setBackendType(AbstractAttributeType::BACKEND_TYPE_DECIMAL);
            $this->getProductManager()->getStorageManager()->persist($productAttribute);
            $messages[]= "Attribute ".$attributeCode." has been created";
        }

        // attribute description (if not exists)
        $attributeCode = 'description';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        if ($attribute) {
            $messages[]= "Attribute ".$attributeCode." already exists";
        } else {
            $productAttribute = $this->getProductManager()->createFlexibleAttribute();
            $productAttribute->setName('Description');
            $productAttribute->getAttribute()->setCode($attributeCode);
            $productAttribute->getAttribute()->setBackendType(AbstractAttributeType::BACKEND_TYPE_TEXT);
            $productAttribute->getAttribute()->setTranslatable(true);
            $this->getProductManager()->getStorageManager()->persist($productAttribute);
            $messages[]= "Attribute ".$attributeCode." has been created";
        }

        // attribute size (if not exists)
        $attributeCode= 'size';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        if ($attribute) {
            $messages[]= "Attribute ".$attributeCode." already exists";
        } else {
            $productAttribute = $this->getProductManager()->createFlexibleAttribute();
            $productAttribute->setName('Size');
            $productAttribute->getAttribute()->setCode($attributeCode);
            $productAttribute->getAttribute()->setBackendType(AbstractAttributeType::BACKEND_TYPE_INTEGER);
            $this->getProductManager()->getStorageManager()->persist($productAttribute);
            $messages[]= "Attribute ".$attributeCode." has been created";
        }

        // attribute color (if not exists)
        $attributeCode= 'color';
        $attribute = $this->getProductManager()->getEntityRepository()->findAttributeByCode($attributeCode);
        if ($attribute) {
            $messages[]= "Attribute ".$attributeCode." already exists";
        } else {
            $productAttribute = $this->getProductManager()->createFlexibleAttribute();
            $productAttribute->setName('Color');
            $productAttribute->getAttribute()->setCode($attributeCode);
            $productAttribute->getAttribute()->setBackendType(AbstractAttributeType::BACKEND_TYPE_OPTION);
            $productAttribute->getAttribute()->setTranslatable(false); // only one value but option can be translated in option values
            // add translatable option and related value "Red", "Blue", "Green"
            $colors = array("Red", "Blue", "Green");
            foreach ($colors as $color) {
                $option = $this->getProductManager()->createAttributeOption();
                $option->setTranslatable(true);
                $optionValue = $this->getProductManager()->createAttributeOptionValue();
                $optionValue->setValue($color);
                $option->addOptionValue($optionValue);
                $productAttribute->getAttribute()->addOption($option);
            }
            $this->getProductManager()->getStorageManager()->persist($productAttribute);
            $messages[]= "Attribute ".$attributeCode." has been created";
        }

        $this->getProductManager()->getStorageManager()->flush();

        $this->get('session')->setFlash('notice', implode(', ', $messages));

        return $this->redirect($this->generateUrl('acme_demoflexibleentity_product_attribute'));
    }

    /**
     * Get product manager
     * @return SimpleEntityManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('customer_manager');
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
     * Get manager
     *
     * @return SimpleEntityManager
     */
    protected function getManufacturerManager()
    {
        return $this->container->get('manufacturer_manager');
    }

}
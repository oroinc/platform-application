<?php
namespace Acme\Bundle\DemoSearchBundle\DataFixtures\ORM\Customer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Oro\Bundle\FlexibleEntityBundle\Model\AbstractAttributeType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\DateType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\TextType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\UrlType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionMultiSelectType;
use Oro\Bundle\FlexibleEntityBundle\Model\AttributeType\OptionSimpleRadioType;

/**
* Load customers
*
* Execute with "php app/console doctrine:fixtures:load"
*
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/MIT MIT
*
*/
class LoadSearchCustomerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
     * @return SimpleManager
     */
    protected function getCustomerManager()
    {
        return $this->container->get('search_customer_manager');
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadAttributes();
        $this->loadCustomers();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 4;
    }

    /**
     * Load attributes
     * @return array()
     */
    public function loadAttributes()
    {
        $messages = array();

        // attribute company (if not exists)
        $attCode = 'company';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        $att = $this->getCustomerManager()->createAttribute(new TextType());
        $att->setCode($attCode);
        $att->setDefaultValue('Acme');
        $this->getCustomerManager()->getStorageManager()->persist($att);
        $messages[]= "Attribute ".$attCode." has been created";

        // attribute date of birth (if not exists)
        $attCode = 'dob';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        $att = $this->getCustomerManager()->createAttribute(new DateType());
        $att->setCode($attCode);
        $this->getCustomerManager()->getStorageManager()->persist($att);
        $messages[]= "Attribute ".$attCode." has been created";

        // attribute date of birth (if not exists)
        $attCode = 'website';
        $att = $this->getCustomerManager()->createAttribute(new UrlType());
        $att->setCode($attCode);
        $this->getCustomerManager()->getStorageManager()->persist($att);
        $messages[]= "Attribute ".$attCode." has been created";

        // attribute gender (if not exists)
        $attCode = 'gender';
        $att = $this->getCustomerManager()->createAttribute(new OptionSimpleRadioType());
        $att->setCode($attCode);
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

        // attribute hobby (if not exists)
        $attCode = 'hobby';
        $att = $this->getCustomerManager()->createAttribute(new OptionMultiSelectType());
        $att->setCode($attCode);
        // add options and related values
        $hobbies = array('Sport', 'Cooking', 'Read', 'Coding!');
        foreach ($hobbies as $hobby) {
            $opt = $this->getCustomerManager()->createAttributeOption();
            $optVal = $this->getCustomerManager()->createAttributeOptionValue();
            $optVal->setValue($hobby);
            $opt->addOptionValue($optVal);
            $att->addOption($opt);
        }
        $this->getCustomerManager()->getStorageManager()->persist($att);
        $messages[]= "Attribute ".$attCode." has been created";

        $this->getCustomerManager()->getStorageManager()->flush();

        return $messages;
    }

    /**
     * Load customers
     * @return array
     */
    public function loadCustomers()
    {
        $messages = array();

        // get attributes
        $attCompany = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('company');
        $attDob = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('dob');
        $attGender = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('gender');
        $attWebsite = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('website');
        $attHobby = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('hobby');
        // get first attribute option
        $optGender = $this->getCustomerManager()->getAttributeOptionRepository()->findOneBy(array('attribute' => $attGender));
        // get attribute hobby options
        $optHobbies = $this->getCustomerManager()->getAttributeOptionRepository()->findBy(array('attribute' => $attHobby));
        $hobbies = array();
        foreach ($optHobbies as $option) {
            $hobbies[]= $option;
        }

        for ($ind= 1; $ind < 10; $ind++) {

            // add customer with email, firstname, lastname, dob
            $custEmail = 'email-'.($ind++).'@mail.com';
            $customer = $this->getCustomerManager()->createFlexible();
            $customer->setEmail($custEmail);
            $customer->setFirstname($this->generateFirstname());
            $customer->setLastname($this->generateLastname());
            // add dob value
            if ($attDob) {
                $value = $customer->getValue($attDob->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attDob);
                    $customer->addValue($value);
                }
                $value->setData(new \DateTime($this->generateBirthDate()));
            }
            $messages[]= "Customer ".$custEmail." has been created";
            $this->getCustomerManager()->getStorageManager()->persist($customer);

            // add customer with email, firstname, lastname, company and gender
            $custEmail = 'email-'.($ind).'@mail.com';
            $customer = $this->getCustomerManager()->createFlexible();
            $customer->setEmail($custEmail);
            $customer->setFirstname($this->generateFirstname());
            $customer->setLastname($this->generateLastname());
            // add company value
            if ($attCompany) {
                $value = $customer->getValue($attCompany->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attCompany);
                    $customer->addValue($value);
                }
                $value->setData('Akeneo');
            }
            // add date of birth
            if ($attDob) {
                $value = $customer->getValue($attDob->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attDob);
                    $customer->addValue($value);
                }
                $value->setData(new \DateTime($this->generateBirthDate()));
            }
            // add website
            if ($attWebsite) {
                $value = $customer->getValue($attWebsite->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attWebsite);
                    $customer->addValue($value);
                }
                $value->setData('http://mywebsite'.$ind.'.com');
            }
            // add gender
            if ($attGender) {
                $value = $customer->getValue($attGender->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attGender);
                    $customer->addValue($value);
                }
                $value->setOption($optGender);
            }
            if ($attHobby) {
                $value = $customer->getValue($attHobby->getCode());
                if (!$value) {
                    $value = $this->getCustomerManager()->createFlexibleValue();
                    $value->setAttribute($attHobby);
                    $customer->addValue($value);
                }
                // pick many hobbies (multiselect)
                $firstHobbyOpt = $hobbies[rand(0, count($hobbies)-1)];
                $value->addOption($firstHobbyOpt);
                $secondHobbyOpt = $hobbies[rand(0, count($hobbies)-1)];
                if ($firstHobbyOpt->getId() != $secondHobbyOpt->getId()) {
                    $value->addOption($secondHobbyOpt);
                }
            }
            $messages[]= "Customer ".$custEmail." has been created";
            $this->getCustomerManager()->getStorageManager()->persist($customer);
        }

        $this->getCustomerManager()->getStorageManager()->flush();

        return $messages;
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
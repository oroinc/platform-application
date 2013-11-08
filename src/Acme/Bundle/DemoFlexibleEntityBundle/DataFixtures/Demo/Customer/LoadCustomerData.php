<?php
namespace Acme\Bundle\DemoFlexibleEntityBundle\DataFixtures\Demo\Customer;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

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
class LoadCustomerData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $this->setCustomerManager();
        //prepare entity counter
        $this->counter = self::DEFAULT_COUNTER_VALUE;
        if (isset($container->counter)) {
            $this->counter = $container->counter;
        } else {
            if ($container->hasParameter('performance.customers')) {
                $this->counter = $container->getParameter('performance.customers');
            }
        }
    }

    /**
     * Set product manager
     */
    public function setCustomerManager()
    {
        $this->manager = $this->container->get('customer_manager');
    }

    /**
     * Get product manager
     * @return SimpleManager
     */
    protected function getCustomerManager()
    {
        return $this->manager;
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
        return 3;
    }

    /**
     * Load attributes
     */
    public function loadAttributes()
    {
        $attCode = 'company';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        if (!$att) {
            $att = $this->getCustomerManager()->createAttribute('oro_flexibleentity_text');
            $att->setCode($attCode);
            $att->setLabel($attCode);
            $att->setSearchable(true);
            $this->getCustomerManager()->getStorageManager()->persist($att);
        }
        $attCode = 'dob';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        if (!$att) {
            $att = $this->getCustomerManager()->createAttribute('oro_flexibleentity_date');
            $att->setCode($attCode);
            $att->setLabel('Date of birth');
            $att->setSearchable(true);
            $this->getCustomerManager()->getStorageManager()->persist($att);
        }

        $attCode = 'website';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        if (!$att) {
            $att = $this->getCustomerManager()->createAttribute('oro_flexibleentity_url');
            $att->setCode($attCode);
            $att->setLabel($attCode);
            $att->setSearchable(true);
            $this->getCustomerManager()->getStorageManager()->persist($att);
        }

        $attCode = 'gender';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        if (!$att) {
            $att = $this->getCustomerManager()->createAttribute('oro_flexibleentity_simpleselect');
            $att->setCode($attCode);
            $att->setLabel($attCode);
            $att->setSearchable(true);
            $opt = $this->getCustomerManager()->createAttributeOption();
            $optVal = $this->getCustomerManager()->createAttributeOptionValue();
            $optVal->setValue('Mr');
            $opt->addOptionValue($optVal);
            $att->addOption($opt);
            $opt = $this->getCustomerManager()->createAttributeOption();
            $optVal = $this->getCustomerManager()->createAttributeOptionValue();
            $optVal->setValue('Mrs');
            $opt->addOptionValue($optVal);
            $att->addOption($opt);
            $this->getCustomerManager()->getStorageManager()->persist($att);
        }

        $attCode = 'hobby';
        $att = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode($attCode);
        if (!$att) {
            $att = $this->getCustomerManager()->createAttribute('oro_flexibleentity_multiselect');
            $att->setCode($attCode);
            $att->setLabel($attCode);
            $att->setSearchable(true);
            $hobbies = array('Sport', 'Cooking', 'Read', 'Coding!');
            foreach ($hobbies as $hobby) {
                $opt = $this->getCustomerManager()->createAttributeOption();
                $optVal = $this->getCustomerManager()->createAttributeOptionValue();
                $optVal->setValue($hobby);
                $opt->addOptionValue($optVal);
                $att->addOption($opt);
            }
            $this->getCustomerManager()->getStorageManager()->persist($att);
        }
        $this->getCustomerManager()->getStorageManager()->flush();
    }

    /**
     * Load customers
     */
    public function loadCustomers()
    {
        $nbCustomers = $this->counter;

        // get attributes
        $attCompany = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('company');
        $attDob = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('dob');
        $attGender = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('gender');
        $attWebsite = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('website');
        $attHobby = $this->getCustomerManager()->getFlexibleRepository()->findAttributeByCode('hobby');
        // get first attribute option
        $optGenders = $this->getCustomerManager()->getAttributeOptionRepository()->findBy(
            array('attribute' => $attGender)
        );
        $genders = array();
        foreach ($optGenders as $option) {
            $genders[]= $option;
        }
        // get attribute hobby options
        $optHobbies = $this->getCustomerManager()->getAttributeOptionRepository()->findBy(
            array('attribute' => $attHobby)
        );
        $hobbies = array();
        foreach ($optHobbies as $option) {
            $hobbies[]= $option;
        }

        for ($ind= 0; $ind < $nbCustomers; $ind++) {

            // add customer with email, firstname, lastname
            $custEmail = 'email-'.($ind).'@mail.com';
            $customer = $this->getCustomerManager()->createFlexible();
            $customer->setEmail($custEmail);
            $customer->setFirstname($this->generateFirstname());
            $customer->setLastname($this->generateLastname());

            // add dob value
            $value = $this->getCustomerManager()->createFlexibleValue();
            $value->setAttribute($attDob);
            $customer->addValue($value);
            $value->setData(new \DateTime($this->generateBirthDate(), new \DateTimeZone('UTC')));

            // add company value
            $value = $this->getCustomerManager()->createFlexibleValue();
            $value->setAttribute($attCompany);
            $customer->addValue($value);
            $value->setData($this->generateCompany());

            // add website
            $value = $this->getCustomerManager()->createFlexibleValue();
            $value->setAttribute($attWebsite);
            $customer->addValue($value);
            $value->setData('http://mywebsite'.$ind.'.com');

            // add gender
            $value = $this->getCustomerManager()->createFlexibleValue();
            $value->setAttribute($attGender);
            $customer->addValue($value);
            $optGender = $genders[rand(0, count($genders)-1)];
            $value->setOption($optGender);

            // pick many hobbies (multiselect)
            $value = $this->getCustomerManager()->createFlexibleValue();
            $value->setAttribute($attHobby);
            $customer->addValue($value);
            $firstHobbyOpt = $hobbies[rand(0, count($hobbies)-1)];
            $value->addOption($firstHobbyOpt);
            $secondHobbyOpt = $hobbies[rand(0, count($hobbies)-1)];
            if ($firstHobbyOpt->getId() != $secondHobbyOpt->getId()) {
                $value->addOption($secondHobbyOpt);
            }

            $this->getCustomerManager()->getStorageManager()->persist($customer);
        }

        $this->getCustomerManager()->getStorageManager()->flush();
    }

    /**
     * Generate firstname
     * @return string
     */
    protected function generateFirstname()
    {
        $listFirstname = array('Nicolas', 'Romain', 'Benoit', 'Filips', 'Frederic', 'Gildas', 'Emilie');
        $random = rand(0, count($listFirstname)-1);

        return $listFirstname[$random];
    }

    /**
     * Generate lastname
     * @return string
     */
    protected function generateLastname()
    {
        $listLastname = array('Dupont', 'Monceau', 'Jacquemont', 'Alpe', 'De Gombert', 'Quemener', 'Gieler');
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
     * Generate company
     * @return string
     */
    protected function generateCompany()
    {
        $list = array('oro', 'akeneo');
        $random = rand(0, count($list)-1);

        return $list[$random];
    }
}

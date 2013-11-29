<?php
namespace Acme\Bundle\DemoGridBundle\DataFixtures\Demo;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\UserBundle\Entity\UserManager;
use Oro\Bundle\UserBundle\Entity\User;

use Oro\Bundle\OrganizationBundle\Entity\Manager\BusinessUnitManager;

/**
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var BusinessUnitManager
     */
    protected $buManager;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->buManager   = $container->get('oro_organization.business_unit_manager');
        $this->userManager = $container->get('oro_user.manager');
    }

    /**
     * {@inheritDoc}
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(ObjectManager $manager)
    {
        $this->loadUsers();
    }

    /**
     * Load users
     *
     * @return void
     */
    public function loadUsers()
    {
        for ($i = 0; $i < 50; ++$i) {
            $firstName = $this->generateFirstName();
            $lastName  = $this->generateLastName();
            $username  = $this->generateUsername($firstName, $lastName);
            $email     = $this->generateEmail($firstName, $lastName);
            $birthday  = $this->generateBirthday();

            $user = $this->createUser(
                $username,
                $email,
                $firstName,
                $lastName,
                $birthday
            );

            $user->setPlainPassword(uniqid());
            $this->userManager->updatePassword($user);

            $this->persist($user);
        }
        $this->flush();
    }

    /**
     * Creates a user
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     *
     * @param string $username
     * @param string $email
     * @param string $firstName
     * @param string $lastName
     * @param \DateTime $birthday
     * @return User
     */
    private function createUser(
        $username,
        $email,
        $firstName,
        $lastName,
        $birthday
    ) {
        /** @var $user User */
        $user = $this->userManager->createUser();
        $businessUnit = $this->buManager->getBusinessUnit();
        
        $user->setEmail($email);
        $user->setUsername($username);
        $user->setFirstname($firstName);
        $user->setLastname($lastName);
        $user->setBirthday($birthday);
        $user->setOwner($businessUnit);
        $user->addBusinessUnit($businessUnit);

        return $user;
    }

    /**
     * Generates a username
     *
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    private function generateUsername($firstName, $lastName)
    {
        $uniqueString = mt_rand(1000, 10000);

        return sprintf("%s.%s_%s", strtolower($firstName), strtolower($lastName), $uniqueString);
    }

    /**
     * Generates an email
     *
     * @param string $firstName
     * @param string $lastName
     * @return string
     */
    private function generateEmail($firstName, $lastName)
    {
        $uniqueString = mt_rand(1000, 10000);
        $domains      = array('yahoo.com', 'gmail.com', 'example.com', 'hotmail.com', 'aol.com', 'msn.com');
        $randomIndex  = rand(0, count($domains) - 1);
        $domain       = $domains[$randomIndex];

        return sprintf("%s.%s_%s@%s", strtolower($firstName), strtolower($lastName), $uniqueString, $domain);
    }

    /**
     * Generate a first name
     *
     * @return string
     */
    private function generateFirstName()
    {
        $firstNamesDictionary = $this->loadDictionary('first_names.txt');
        $randomIndex          = rand(0, count($firstNamesDictionary) - 1);

        return trim($firstNamesDictionary[$randomIndex]);
    }

    /**
     * Loads dictionary from file by name
     *
     * @param string $name
     * @return array
     */
    private function loadDictionary($name)
    {
        static $dictionaries = array();

        if (!isset($dictionaries[$name])) {
            $dictionary = array();
            $fileName   = __DIR__ . DIRECTORY_SEPARATOR . 'dictionaries' . DIRECTORY_SEPARATOR . $name;
            foreach (file($fileName) as $item) {
                $dictionary[] = trim($item);
            }
            $dictionaries[$name] = $dictionary;
        }

        return $dictionaries[$name];
    }

    /**
     * Generates a last name
     *
     * @return string
     */
    private function generateLastName()
    {
        $lastNamesDictionary = $this->loadDictionary('last_names.txt');
        $randomIndex         = rand(0, count($lastNamesDictionary) - 1);

        return trim($lastNamesDictionary[$randomIndex]);
    }

    /**
     * Generates a date of birth
     *
     * @return \DateTime
     */
    private function generateBirthday()
    {
        // Convert to timetamps
        $min = strtotime('1950-01-01');
        $max = strtotime('2000-01-01');

        // Generate random number using above bounds
        $val = rand($min, $max);

        // Convert back to desired date format
        return new \DateTime(date('Y-m-d', $val), new \DateTimeZone('UTC'));
    }


    /**
     * Persist object
     *
     * @param mixed $object
     * @return void
     */
    private function persist($object)
    {
        $this->userManager->getStorageManager()->persist($object);
    }

    /**
     * Flush objects
     *
     * @return void
     */
    private function flush()
    {
        $this->userManager->getStorageManager()->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}

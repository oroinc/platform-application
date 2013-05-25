<?php

namespace Acme\Bundle\DemoFormBundle\Form\Model;

use Symfony\Component\HttpFoundation\File\File;

class Person
{
    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $plainPassword;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $about;

    /**
     * @var string
     */
    private $gender;

    /**
     * @var \DateTime
     */
    private $dob;

    /**
     * @var array
     */
    private $hobbies;

    /**
     * @var array
     */
    private $subscriptions;

    /**
     * @var bool
     */
    private $acceptTerms;

    /**
     * @var File
     */
    private $avatar;

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return string
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return string
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param \DateTime $dob
     */
    public function setDob(\DateTime $dob = null)
    {
        $this->dob = $dob;
    }

    /**
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param array $hobbies
     */
    public function setHobbies(array $hobbies)
    {
        $this->hobbies = $hobbies;
    }

    /**
     * @return array
     */
    public function getHobbies()
    {
        return $this->hobbies;
    }

    /**
     * @param array $subscriptions
     */
    public function setSubscriptions(array $subscriptions)
    {
        $this->subscriptions = $subscriptions;
    }

    /**
     * @return array
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }

    /**
     * @param boolean $acceptTerms
     */
    public function setAcceptTerms($acceptTerms)
    {
        $this->acceptTerms = $acceptTerms;
    }

    /**
     * @return boolean
     */
    public function getAcceptTerms()
    {
        return $this->acceptTerms;
    }

    /**
     * @param File|string $avatar
     */
    public function setAvatar($avatar)
    {
        if (is_string($avatar)) {
            $avatar = new File($avatar, false);
        } elseif (!$avatar instanceof File) {
            throw new \InvalidArgumentException(
                '$avatar must be an instance of Symfony\Component\HttpFoundation\File\File or a string'
            );
        }
        $this->avatar = $avatar;
    }

    /**
     * @return File
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}

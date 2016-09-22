<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sonata\UserBundle\Entity\BaseUser as BaseUser;

/**
 * User model and constants definitions to emulate Enum type behaviour.
 */
class User extends BaseUser
{
    const TITLE_MR = 'mr';
    const TITLE_MS = 'ms';
    const TITLE_MRS = 'mrs';
    const TITLE_MISS = 'miss';
    const TITLE_DR = 'dr';
    const TITLE_PROF = 'prof';

    const GENDER_UNKNOWN = 'gender_unknown';
    const GENDER_FEMALE = 'gender_female';
    const GENDER_MALE = 'gender_male';

    const QUESTION_SPOUSE = 'spouse_name';
    const QUESTION_MAIDEN_NAME = 'maiden_name';
    const QUESTION_CAR = 'first_car';
    const QUESTION_PET = 'first_pet';
    const QUESTION_SCHOOL = 'first_school';

    const CURRENCY_POUND = 'GBP';
    const CURRENCY_EURO = 'EUR';
    const CURRENCY_USD = 'USD';

    const LANGUAGE_EN = 'en';
    const COUNTRY_EN = 'GB';
    const TIMEZONE_LONDON = 'London';
    const CAMPAIGN_REGISTER = 'register';

    /**
     * Hook on pre-persist operations.
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Hook on pre-update operations.
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * default to unknown
     */
    protected $gender = self::GENDER_UNKNOWN;

    /**
     * @var string
     *
     * default to London
     */
    protected $timezone = self::TIMEZONE_LONDON;

    /**
     * @var string
     */
    protected $confirmed;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     *
     * default to en
     */
    protected $language = self::LANGUAGE_EN;

    /**
     * @var string
     */
    protected $secretQuestion;

    /**
     * @var string
     */
    protected $secretQuestionResponse;

    /**
     * @var string
     */
    protected $addressLine1;

    /**
     * @var string
     */
    protected $addressLine2;

    /**
     * @var string
     */
    protected $addressLine3;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $county;

    /**
     * @var string
     */
    protected $postcode;

    /**
     * @var string
     *
     * default to GB
     */
    protected $countryCode = self::COUNTRY_EN;

    /**
     * @var string
     *
     * default to GBP
     */
    protected $currencyCode = self::CURRENCY_POUND;

    /**
     * @var string
     */
    protected $mobile;

    /**
     * @var bool
     */
    protected $termsAccepted;

    /**
     * @var string
     *
     * default to register
     */
    protected $campaign = self::CAMPAIGN_REGISTER;

    /**
     * @var bool
     */
    protected $isSystemUser;

    /**
     * @var int
     */
    protected $failedAttempts;

    /**
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="userAvatar", referencedColumnName="id",  nullable=true, onDelete="SET NULL")
     */
    protected $userAvatar = null;

    /**
     * @ORM\OneToOne(targetEntity="\Application\Sonata\MediaBundle\Entity\Media", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="userHeroImage", referencedColumnName="id",  nullable=true, onDelete="SET NULL")
     */
    protected $userHeroImage = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $gender
     *
     * @return User
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param string $timezone
     *
     * @return User
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return string
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param string $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
    }

    /**
     * @return string
     */
    public function isConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @return string
     */
    public function getSecretQuestion()
    {
        return $this->secretQuestion;
    }

    /**
     * @param string $secretQuestion
     */
    public function setSecretQuestion($secretQuestion)
    {
        $this->secretQuestion = $secretQuestion;
    }

    /**
     * @return string
     */
    public function getSecretQuestionResponse()
    {
        return $this->secretQuestionResponse;
    }

    /**
     * @param string $secretQuestionResponse
     */
    public function setSecretQuestionResponse($secretQuestionResponse)
    {
        $this->secretQuestionResponse = $secretQuestionResponse;
    }

    /**
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @param string $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @param string $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @return string
     */
    public function getAddressLine3()
    {
        return $this->addressLine3;
    }

    /**
     * @param string $addressLine3
     */
    public function setAddressLine3($addressLine3)
    {
        $this->addressLine3 = $addressLine3;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCounty()
    {
        return $this->county;
    }

    /**
     * @param string $county
     */
    public function setCounty($county)
    {
        $this->county = $county;
    }

    /**
     * @return string
     */
    public function getPostcode()
    {
        return $this->postcode;
    }

    /**
     * @param string $postcode
     */
    public function setPostcode($postcode)
    {
        $this->postcode = $postcode;
    }

    /**
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @return bool
     */
    public function isTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * @param bool $termsAccepted
     */
    public function setTermsAccepted($termsAccepted)
    {
        $this->termsAccepted = $termsAccepted;
    }

    /**
     * @return string
     */
    public function getCampaign()
    {
        return $this->campaign;
    }

    /**
     * @param string $campaign
     */
    public function setCampaign($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * @return bool
     */
    public function isIsSystemUser()
    {
        return $this->isSystemUser;
    }

    /**
     * @param bool $isSystemUser
     */
    public function setIsSystemUser($isSystemUser)
    {
        $this->isSystemUser = $isSystemUser;
    }

    /**
     * @return int
     */
    public function getFailedAttempts()
    {
        return $this->failedAttempts;
    }

    /**
     * @param int $failedAttempts
     */
    public function setFailedAttempts($failedAttempts)
    {
        $this->failedAttempts = $failedAttempts;
    }

    /**
     * Set userAvatar.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $userAvatar
     *
     * @return User
     */
    public function setUserAvatar(\Application\Sonata\MediaBundle\Entity\Media $userAvatar = null)
    {
        $this->userAvatar = $userAvatar;

        return $this;
    }

    /**
     * Get userAvatar.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * Set userHeroImage.
     *
     * @param \Application\Sonata\MediaBundle\Entity\Media $userHeroImage
     *
     * @return User
     */
    public function setUserHeroImage(\Application\Sonata\MediaBundle\Entity\Media $userHeroImage = null)
    {
        $this->userHeroImage = $userHeroImage;

        return $this;
    }

    /**
     * Get userHeroImage.
     *
     * @return \Application\Sonata\MediaBundle\Entity\Media
     */
    public function getUserHeroImage()
    {
        return $this->userHeroImage;
    }

    /**
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        // Enable registration only with email address
        /*
        if ($this->username === null) {
            $this->username = $email;
            $this->password = $email;
            $this->plainPassword = $email;
        }
        */

        return $this;
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return sprintf('%s %s', $this->getFirstname(), $this->getLastname());
    }

    /**
     * Returns the gender list.
     *
     * @return array
     */
    public static function getGenderList()
    {
        return array(
            self::GENDER_UNKNOWN => 'gender_unknown',
            self::GENDER_FEMALE => 'gender_female',
            self::GENDER_MALE => 'gender_male',
        );
    }

    /**
     * Returns the title list.
     *
     * @return array
     */
    public static function getTitleList()
    {
        return array(
            self::TITLE_MR => 'mr',
            self::TITLE_MS => 'ms',
            self::TITLE_MRS => 'mrs',
            self::TITLE_MISS => 'miss',
            self::TITLE_DR => 'dr',
            self::TITLE_PROF => 'prof',
        );
    }

    /**
     * Returns the secret question list.
     *
     * @return array
     */
    public static function getSecretQuestionList()
    {
        return array(
            self::QUESTION_SPOUSE => 'spouse_name',
            self::QUESTION_MAIDEN_NAME => 'maiden_name',
            self::QUESTION_CAR => 'first_car',
            self::QUESTION_PET => 'first_pet',
            self::QUESTION_SCHOOL => 'first_school',
        );
    }

    /**
     * Returns the secret question list.
     *
     * @return array
     */
    public static function getCurrencyCodeList()
    {
        return array(
            self::CURRENCY_POUND => 'GBP',
            self::CURRENCY_EURO => 'EUR',
            self::CURRENCY_USD => 'USD',
        );
    }

    /**
     * Returns if Date of birth is valid.
     *
     * @return bool
     */
    public function isValidDateOfBirth()
    {
        $todaysDate = new \DateTime();

        return $this->dateOfBirth < $todaysDate;
    }

    /**
     * Returns if password is legal.
     *
     * @return bool
     */
    public function isSafePassword()
    {
        return ($this->email !== $this->password) && ($this->username !== $this->password);
    }

    /**
     * Returns a string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ?: '-';
    }
}

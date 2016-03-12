<?php

/**
 * This file is part of the <name> project.
 *
 * (c) <yourname> <youremail>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * User
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
    const GENDER_FEMALE  = 'gender_female';
    const GENDER_MALE    = 'gender_male';

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
     * @var integer
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
    protected $gender = USER::GENDER_UNKNOWN;

    /**
     * @var string
     *
     * default to London
     */
    protected $timezone = User::TIMEZONE_LONDON;

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
    protected $language = User::LANGUAGE_EN;

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
    protected $countryCode = User::COUNTRY_EN;

    /**
     * @var string
     *
     * default to GBP
     */
    protected $currencyCode = User::CURRENCY_POUND;

    /**
     * @var string
     */
    protected $mobile;

    /**
     * @var boolean
     */
    protected $termsAccepted;

    /**
     * @var string
     *
     * default to register
     */
    protected $campaign = User::CAMPAIGN_REGISTER;

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
     * @return boolean
     */
    public function isTermsAccepted()
    {
        return $this->termsAccepted;
    }

    /**
     * @param boolean $termsAccepted
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
            User::GENDER_UNKNOWN => 'gender_unknown',
            User::GENDER_FEMALE  => 'gender_female',
            User::GENDER_MALE    => 'gender_male',
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
            User::TITLE_MR      => 'mr',
            User::TITLE_MS      => 'ms',
            User::TITLE_MRS     => 'mrs',
            User::TITLE_MISS    => 'miss',
            User::TITLE_DR      => 'dr',
            User::TITLE_PROF    => 'prof',
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
            User::QUESTION_SPOUSE       => 'spouse_name',
            User::QUESTION_MAIDEN_NAME  => 'maiden_name',
            User::QUESTION_CAR          => 'first_car',
            User::QUESTION_PET          => 'first_pet',
            User::QUESTION_SCHOOL       => 'first_school',
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
            User::CURRENCY_POUND    => 'GBP',
            User::CURRENCY_EURO     => 'EUR',
            User::CURRENCY_USD      => 'USD',
        );
    }

    /**
     * Returns if Date of birth is valid.
     *
     * @return boolean
     */
    public function isValidDateOfBirth()
    {
        $todaysDate = new \DateTime();

        return ($this->dateOfBirth < $todaysDate);
    }

    /**
     * Returns if password is legal.
     *
     * @return boolean
     */
    public function isSafePassword()
    {
        return ($this->email != $this->password) && ($this->username != $this->password);
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

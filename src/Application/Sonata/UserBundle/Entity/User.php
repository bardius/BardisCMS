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
use Application\Sonata\UserBundle\Entity\BookieUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sonata\UserBundle\Model\UserInterface;


class User extends BaseUser
{
    const TITLE_MR = 'mr';
    const TITLE_MS = 'ms';
    const TITLE_MRS = 'mrs';
    const TITLE_MISS = 'miss';
    const TITLE_DR = 'dr';
    const TITLE_PROF = 'prof';

    const QUESTION_SPOUSE = 'spouse_name';
    const QUESTION_MAIDEN_NAME = 'maiden_name';
    const QUESTION_CAR = 'first_car';
    const QUESTION_PET = 'first_pet';
    const QUESTION_SCHOOL = 'first_school';

    const CURRENCY_POUND = 'GBP';
    const CURRENCY_EURO = 'EUR';
    const CURRENCY_USD = 'USD';

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
     * @ORM\Column(name="confirmed", type="boolean", length=1)
     */
    protected $confirmed;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=8)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=2)
     */
    protected $language;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_question", type="string", length=180)
     */
    protected $secretQuestion;

    /**
     * @var string
     *
     * @ORM\Column(name="secret_question_response", type="string", length=180)
     */
    protected $secretQuestionResponse;

    /**
     * @var string
     *
     * @ORM\Column(name="addressLine1", type="string", length=180)
     */
    protected $addressLine1;

    /**
     * @var string
     *
     * @ORM\Column(name="addressLine2", type="string", length=180)
     */
    protected $addressLine2;

    /**
     * @var string
     *
     * @ORM\Column(name="addressLine3", type="string", length=180)
     */
    protected $addressLine3;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=60)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="county", type="string", length=60)
     */
    protected $county;

    /**
     * @var string
     *
     * @ORM\Column(name="postcode", type="string", length=60)
     */
    protected $postcode;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=2)
     */
    protected $countryCode;

    /**
     * @var string
     *
     * @ORM\Column(name="currency_code", type="string", length=3)
     */
    protected $currencyCode;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=15)
     */
    protected $mobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="terms_accepted", type="boolean", length=1)
     */
    protected $termsAccepted;

    /**
     * @var string
     *
     * @ORM\Column(name="campaign", type="string", length=80)
     */
    protected $campaign;

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
        if ($this->getUsername() === null) {
            $this->username = $email;
            $this->password = $email;
            $this->plainPassword = $email;
        }
    }

    public function setEmailCanonical($emailCanonical)
    {
        $this->emailCanonical = $emailCanonical;
        if ($this->getUsernameCanonical() === null) {
            $this->usernameCanonical = $emailCanonical;
        }
    }

    /**
     * @return string
     */
    public function getFullname()
    {
        return $this->getFirstname() . " " . $this->getLastname();
    }

    /**
     * Returns the gender list.
     *
     * @return array
     */
    public static function getGenderList()
    {
        return array(
            UserInterface::GENDER_UNKNOWN => 'gender_unknown',
            UserInterface::GENDER_FEMALE  => 'gender_female',
            UserInterface::GENDER_MALE    => 'gender_male',
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
     * Returns a string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getUsername() ? $this->getUsername() : '-';
    }

}

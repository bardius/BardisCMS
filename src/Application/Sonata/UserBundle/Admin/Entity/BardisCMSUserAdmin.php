<?php

/*
 * Sonata User Bundle
 * This file is part of the BardisCMS.
 * Manage the extended the Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Admin\Entity;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use FOS\UserBundle\Model\UserManagerInterface;

use Application\Sonata\UserBundle\Entity\User;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;

class BardisCMSUserAdmin extends BaseUserAdmin {

    protected $formOptions = array(
        'validation_groups' => array(
            'OverriddenProfile'
        )
    );

    /**
     * {@inheritdoc}
     */
    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'OverriddenProfile';

        $formBuilder = $this->getFormContractor()->getFormBuilder($this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('id')
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled', null, array('editable' => true))
            ->add('confirmed', null, array('editable' => false))
            ->add('locked', null, array('editable' => true))
            ->add('createdAt')
            ->add('groups')
            ->add('roles', null, array('editable' => true), array('translation_domain' => 'SonataUserBundle'))
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureDatagridFilters(DatagridMapper $filterMapper) {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('confirmed')
            ->add('locked')
            ->add('groups')
            ->add('roles')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureShowFields(ShowMapper $showMapper) {
        $showMapper
            ->with('Generic Details')
                ->add('email', null, array(
                    'label' => 'form.email',
                    'translation_domain' => 'SonataUserBundle'
                ))
                ->add('username', null, array(
                    'label' => 'form.username',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('title', null, array(
                    'label' => 'form.title',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('firstname', null, array(
                    'label' => 'form.firstname',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('lastname', null, array(
                    'label' => 'form.lastname',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('gender', null, array(
                    'label' => 'form.gender',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('dateOfBirth', null, array(
                    'label' => 'form.dateOfBirth',
                    'translation_domain' => 'SonataUserBundle',
                ))
            ->end()
            ->with('Account Preferences')
                ->add('language', null, array(
                    'label' => 'form.language',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('currencyCode', null, array(
                    'label' => 'form.currencyCode',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('biography', null, array(
                    'label' => 'form.biography',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('website', null, array(
                    'label' => 'form.website',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('timezone', null, array(
                    'label' => 'form.timezone',
                    'translation_domain' => 'SonataUserBundle',
                ))
            ->end()
            ->with('Contact Details')
                ->add('addressLine1', null, array(
                    'label' => 'form.addressLine1',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('addressLine2', null, array(
                    'label' => 'form.addressLine2',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('addressLine3', null, array(
                    'label' => 'form.addressLine3',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('city', null, array(
                    'label' => 'form.city',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('county', null, array(
                    'label' => 'form.county',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('postCode', null, array(
                    'label' => 'form.postcode',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('countryCode', null, array(
                    'label' => 'form.countryCode',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('phone', null, array(
                    'label' => 'form.phone',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('mobile', null, array(
                    'label' => 'form.mobile',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('campaign', null, array(
                    'label' => 'form.campaignname',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('termsAccepted', null, array(
                    'label' => 'form.tnc',
                    'translation_domain' => 'SonataUserBundle',
                ))
            ->end()
            ->with('Security')
                ->add('secretQuestion',null, array(
                    'label' => 'form.secretQuestion',
                    'translation_domain' => 'SonataUserBundle',
                ))
                ->add('secretQuestionResponse',null, array(
                    'label' => 'form.secretQuestionResponse',
                    'translation_domain' => 'SonataUserBundle',
                ))
            ->end()
            ->with('Groups')
                ->add('groups')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureFormFields(FormMapper $formMapper) {
        $now = new \DateTime();

        $formMapper
            ->tab('Generic Details')
                ->with('Generic Details', array('collapsed' => false))
                    ->add('email', EmailType::class, array(
                        'label' => 'form.email',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('username', TextType::class, array(
                        'label' => 'form.username',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('title', ChoiceType::class, array(
                        'choices' => array(
                            User::TITLE_MR      => 'mr',
                            User::TITLE_MS      => 'ms',
                            User::TITLE_MRS     => 'mrs',
                            User::TITLE_MISS    => 'miss',
                            User::TITLE_DR      => 'dr',
                            User::TITLE_PROF    => 'prof',
                        ),
                        'label' => 'form.title',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true,
                        'expanded' => false,
                        'multiple' => false
                    ))
                    ->add('firstname', TextType::class, array(
                        'label' => 'form.firstname',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('lastname', TextType::class, array(
                        'label' => 'form.lastname',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('gender', ChoiceType::class, array(
                        'choices' => array(
                            User::GENDER_UNKNOWN    => 'gender_unknown',
                            User::GENDER_FEMALE     => 'gender_female',
                            User::GENDER_MALE       => 'gender_male'
                        ),
                        'label' => 'form.gender',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true,
                        'expanded' => false,
                        'multiple' => false
                    ))
                    ->add('dateOfBirth', DateType::class, array(
                        'format' => 'dd-MM-yyyy',
                        'widget' => 'single_text',
                        'label' => 'form.dateOfBirth',
                        'translation_domain' => 'SonataUserBundle',
                        'placeholder' => 'dd-mm-yyyy',
                        'html5' => true,
                        'error_bubbling' => false,
                        'attr' => [
                            'class' => 'datepicker datepickerField',
                            'data-date-language' => 'en',
                            'data-date-start-date' => '01-01-1902',
                            'data-date-end-date' => $now->format('d-m-Y'),
                            'data-date-format' => 'DD-MM-YYYY',
                            'data-date-pick-time' => false,
                            'placeholder' => "dd-mm-yyyy",
                            'data-picker-position' => 'bottom-right'
                        ],
                        'invalid_message' => 'sonata_user.dateOfBirth.isNotDate',
                        'required' => false
                    ))
                ->end()
            ->end()
            ->tab('Account Preferences')
                ->with('Account Preferences', array('collapsed' => true))
                    ->add('language', LanguageType::class, array(
                        'preferred_choices' => array(
                            User::LANGUAGE_EN
                        ),
                        'label' => 'form.language',
                        'translation_domain' => 'SonataUserBundle',
                        'expanded' => false,
                        'multiple' => false,
                        'required' => true
                    ))
                    ->add('currencyCode', ChoiceType::class, array(
                        'choices' => array(
                            User::CURRENCY_POUND    => 'GBP',
                            User::CURRENCY_EURO     => 'EUR',
                            User::CURRENCY_USD      => 'USD'
                        ),
                        'preferred_choices' => array(
                            User::CURRENCY_POUND    => 'GBP',
                        ),
                        'label' => 'form.currencyCode',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true,
                        'expanded' => true,
                        'multiple' => false
                    ))
                    ->add('biography', TextareaType::class, array(
                        'label' => 'form.biography',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('website', UrlType::class, array(
                        'label' => 'form.website',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('timezone', TimezoneType::class, array(
                        'preferred_choices' => array(
                            User::TIMEZONE_LONDON
                        ),
                        'label' => 'form.timezone',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                ->end()
            ->end()
            ->tab('Contact Details')
                ->with('Contact Details', array('collapsed' => true))
                    ->add('addressLine1', TextType::class, array(
                        'label' => 'form.addressLine1',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('addressLine2', TextType::class, array(
                        'label' => 'form.addressLine2',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('addressLine3', TextType::class, array(
                        'label' => 'form.addressLine3',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('city', TextType::class, array(
                        'label' => 'form.city',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('county', TextType::class, array(
                        'label' => 'form.county',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('postcode', TextType::class, array(
                        'label' => 'form.postcode',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('countryCode', CountryType::class, array(
                        'preferred_choices' => array(
                            User::COUNTRY_EN
                        ),
                        'label' => 'form.countryCode',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                    ->add('phone', TextType::class, array(
                        'label' => 'form.phone',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('mobile', TextType::class, array(
                        'label' => 'form.mobile',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('campaign', TextType::class, array(
                        'label' => 'form.campaignname',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('termsAccepted', CheckboxType::class, array(
                        'label' => 'form.tnc',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => true
                    ))
                ->end()
            ->end()
            ->tab('Security')
                ->with('Security', array('collapsed' => true))
                    ->add('plainPassword', TextType::class, array(
                        'label' => 'form.plainPassword',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('secretQuestion', ChoiceType::class, array(
                        'choices' => array(
                            User::QUESTION_SPOUSE       => 'spouse_name',
                            User::QUESTION_MAIDEN_NAME  => 'maiden_name',
                            User::QUESTION_CAR          => 'first_car',
                            User::QUESTION_PET          => 'first_pet',
                            User::QUESTION_SCHOOL       => 'first_school'
                        ),
                        'label' => 'form.secretQuestion',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('secretQuestionResponse', TextType::class, array(
                        'label' => 'form.secretQuestionResponse',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('token', TextType::class, array(
                        'label' => 'form.token',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                    ->add('twoStepVerificationCode', TextType::class, array(
                        'label' => 'form.verificationCode',
                        'translation_domain' => 'SonataUserBundle',
                        'required' => false
                    ))
                ->end()
            ->end()
            ->tab('Groups')
                ->with('Groups', array('collapsed' => true))
                    ->add('groups', 'sonata_type_model', array(
                        'required' => false,
                        'expanded' => true,
                        'multiple' => true
                    ))
                ->end()
            ->end()
        ;

        if (!$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('User Management')
                    ->with('User Management', array('collapsed' => true))
                        ->add('locked', null, array(
                            'label' => 'form.locked',
                            'translation_domain' => 'SonataUserBundle',
                            'required' => false
                        ))
                        ->add('confirmed', null, array(
                            'label' => 'form.confirmed',
                            'translation_domain' => 'SonataUserBundle',
                            'required' => false
                        ))
                        ->add('expired', null, array(
                            'label' => 'form.expired',
                            'translation_domain' => 'SonataUserBundle',
                            'required' => false
                        ))
                        ->add('enabled', null, array(
                            'label' => 'form.enabled',
                            'translation_domain' => 'SonataUserBundle',
                            'required' => false
                        ))
                        ->add('credentialsExpired', null, array(
                            'label' => 'form.credentialsExpired',
                            'translation_domain' => 'SonataUserBundle',
                            'required' => false
                        ))
                        ->add('roles', 'sonata_security_roles', array(
                            'label' => 'form.roles',
                            'translation_domain' => 'SonataUserBundle',
                            'expanded' => true,
                            'multiple' => true,
                            'required' => false
                        ))
                    ->end()
                ->end()
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function preUpdate($user) {
        $this->getUserManager()->updateCanonicalFields($user);
        $this->getUserManager()->updatePassword($user);
    }

    /**
     * @param UserManagerInterface $userManager
     */
    public function setUserManager(UserManagerInterface $userManager) {
        $this->userManager = $userManager;
    }

    /**
     * @return UserManagerInterface
     */
    public function getUserManager() {
        return $this->userManager;
    }

}

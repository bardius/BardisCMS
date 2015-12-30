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
use Sonata\UserBundle\Model\UserInterface;

class BardisCMSUserAdmin extends BaseUserAdmin {

    protected $formOptions = array(
        'validation_groups' => 'Profile'
    );

    /**
     * {@inheritdoc}
     */
    public function configureListFields(ListMapper $listMapper) {
        $listMapper
                ->addIdentifier('username')
                ->add('email')
                ->add('groups')
                ->add('enabled', null, array('editable' => true))
                ->add('locked', null, array('editable' => true))
                ->add('createdAt')
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
                ->add('locked')
                ->add('email')
                ->add('groups')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureShowFields(ShowMapper $showMapper) {
        $showMapper
            ->with('General')
                ->add('username', null, array('label' => 'Username'))
                ->add('email', null, array('label' => 'eMail'))
            ->end()
            ->with('Profile')
                ->add('title', null, array('label' => 'Title'))
                ->add('firstname', null, array('label' => 'First Name'))
                ->add('lastname', null, array('label' => 'Last Name'))
                ->add('gender', null, array('label' => 'Gender'))
                ->add('dateOfBirth', null, array('label' => 'Date Of Birth'))
            ->end()
            ->with('Contact Details')
                ->add('addressLine1', null, array('label' => 'Address Line 1'))
                ->add('addressLine2', null, array('label' => 'Address Line 2'))
                ->add('addressLine3', null, array('label' => 'Address Line 3'))
                ->add('city', null, array('label' => 'City'))
                ->add('county', null, array('label' => 'County'))
                ->add('countryCode', null, array('label' => 'Country'))
                ->add('postCode', null, array('label' => 'Postcode'))
                ->add('phone', null, array('label' => 'Phone'))
                ->add('mobile', null, array('label' => 'Mobile'))
                ->add('campaign', null, array('label' => 'Onboarding Campaign Name'))
            ->end()
            ->with('Account Preferences')
                ->add('language', null, array('label' => 'Language'))
                ->add('currencyCode', null, array('label' => 'Currency'))
                ->add('termsAccepted', null, array('label' => 'Accepted T&Cs'))
            ->end()
            ->with('Security')
                ->add('secretQuestion',null, array('label' => 'Secret Question'))
                ->add('secretQuestionResponse',null, array('label' => 'Secret Question Response'))
                ->add('token')
                ->add('twoStepVerificationCode')
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
        $formMapper
            ->tab('General')
                ->with('General', array('collapsed' => false))
                    ->add('username', null, array('label' => 'Username', 'required' => true))
                    ->add('email', null, array('label' => 'email', 'required' => true))
                    ->add('plainPassword', 'text', array('required' => false))
                ->end()
            ->end()
            ->tab('Profile')
                ->with('Profile', array('collapsed' => true))
                    ->add('title', 'choice', array('choices' => array(
                        'mr' => 'Mr',
                        'ms' => 'Ms',
                        'mrs' => 'Mrs',
                        'miss' => 'Miss',
                        'dr' => 'Dr',
                        'prof' => 'Prof'
                    ), 'label' => 'Title', 'required' => true, 'expanded' => true, 'multiple' => false))
                    ->add('firstname', null, array('label' => 'First Name', 'required' => true))
                    ->add('lastname', null, array('label' => 'Surname', 'required' => true))
                    ->add('gender', 'choice', array('choices' => array(
                        UserInterface::GENDER_UNKNOWN => 'gender_unknown',
                        UserInterface::GENDER_FEMALE  => 'gender_female',
                        UserInterface::GENDER_MALE    => 'gender_male',
                    ), 'label' => 'Gender', 'required' => true, 'expanded' => true, 'multiple' => false))
                    ->add('dateOfBirth', 'birthday', array('format' => 'yyyy-MM-dd', 'widget' => 'single_text', 'label' => 'Date Of Birth', 'required' => true))
                ->end()
            ->end()
            ->tab('Contact Details')
                ->with('Contact Details', array('collapsed' => true))
                    ->add('addressLine1', 'text', array('label' => 'Address Line 1', 'required' => true))
                    ->add('addressLine2', 'text', array('label' => 'Address Line 2', 'required' => true))
                    ->add('addressLine3', 'text', array('label' => 'Address Line 3', 'required' => false))
                    ->add('city', 'text', array('label' => 'City', 'required' => true))
                    ->add('county', 'text', array('label' => 'County', 'required' => false))
                    ->add('postCode', 'text', array('label' => 'Postcode', 'required' => true))
                    ->add('countryCode', 'country', array('preferred_choices' => array(
                        'GB' => 'UK',
                        'US' => 'USA'
                    ), 'label' => 'Country', 'required' => true))
                    ->add('phone', 'text', array('label' => 'Phone', 'required' => true))
                    ->add('mobile', 'text', array('label' => 'Mobile', 'required' => true))
                ->end()
            ->end()
            ->tab('Account Preferences')
                ->with('Account Preferences', array('collapsed' => true))
                    ->add('language', 'language', array('preferred_choices' => array(
                        'en' => 'English'
                    ),'label' => 'Language', 'required' => true, 'expanded' => false, 'multiple' => false))
                    ->add('currencyCode', 'choice', array('choices' => array(
                        'GBP' => '£' ,
                        'USD' => '$',
                        'EUR' => '€'
                    ), 'label' => 'Currency', 'required' => true))
                    ->add('termsAccepted', null, array('label' => 'Accepted T&Cs', 'required' => false))
                ->end()
            ->end()
        ;

        $formMapper
            ->tab('Security')
                ->with('Security', array('collapsed' => true))
                    ->add('secretQuestion','choice', array('choices' => array(
                        'Spouse’s middle name' => 'Spouse’s middle name',
                        'Mother’s Maiden Name' => 'Mother’s Maiden Name',
                        'My favourite player' => 'My favourite player',
                        'My first car' => 'My first car',
                        'My first pet’s name' => 'My first pet’s name',
                        'My first school' => 'My first school'
                    ),'label' => 'Challenge','required' => true))
                    ->add('secretQuestionResponse','text', array('label' => 'Response','required' => true))
                    ->add('token', null, array('required' => false))
                    ->add('twoStepVerificationCode', null, array('required' => false))
                ->end()
            ->end()
            ->tab('Groups')
                ->with('Groups', array('collapsed' => true))
                    ->add('groups', 'sonata_type_model', array('required' => false, 'expanded' => true, 'multiple' => true))
                ->end()
            ->end()
        ;


        if (!$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->tab('User Management')
                    ->with('User Management', array('collapsed' => true))
                        ->add('roles', 'sonata_security_roles', array(
                            'expanded' => true,
                            'multiple' => true,
                            'required' => false
                        ))
                        ->add('locked', null, array('required' => false))
                        ->add('expired', null, array('required' => false))
                        ->add('enabled', null, array('required' => false))
                        ->add('credentialsExpired', null, array('required' => false))
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

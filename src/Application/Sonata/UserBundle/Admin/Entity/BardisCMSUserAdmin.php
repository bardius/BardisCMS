<?php

/*
 * Blog Bundle
 * This file is part of the BardisCMS.
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
                ->add('username')
                ->add('email')
                ->end()
                ->with('Profile')
                ->add('firstname')
                ->add('lastname')
                ->add('sex')
                ->add('bakeFrequency')
                ->add('bakeChoises')
                ->add('children')
                ->add('campaign')
                ->end()
                ->with('Groups')
                ->add('groups')
                ->end()
                ->with('Security')
                ->add('token')
                ->add('twoStepVerificationCode')
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
                ->add('username')
                ->add('email')
                ->add('plainPassword', 'text', array('required' => false))
                ->end()
                ->end()
                ->tab('Profile')
                ->with('Profile', array('collapsed' => true))
                ->add('firstname', null, array('label' => 'First Name', 'required' => false))
                ->add('lastname', null, array('label' => 'Surname', 'required' => false))
                ->add('sex', 'choice', array('choices' => array('male' => 'Male', 'female' => 'Female'), 'label' => 'Sex', 'required' => false, 'expanded' => true, 'multiple' => false))
                ->add('bakeFrequency', 'choice', array('choices' => array('year' => 'Once a year', 'month' => 'Once a year', 'week' => 'Every Week'), 'label' => 'How often do you bake?', 'required' => false))
                ->add('bakeChoises', 'choice', array('choices' => array('biscuits' => 'biscuits', 'breads' => 'breads', 'brownies' => 'brownies', 'cakes' => 'cakes', 'cupcakes' => 'cupcakes', 'desserts' => 'desserts', 'muffins' => 'muffins', 'pancakes' => 'pancakes', 'pies' => 'pies'), 'label' => 'Which of the following do you bake?', 'required' => false, 'expanded' => true, 'multiple' => true))
                ->add('children', 'choice', array('choices' => array('no' => 'No', 'yes' => 'Yes'), 'label' => 'Do you have children?', 'required' => false, 'expanded' => true, 'multiple' => false))
                ->add('campaign', null, array('label' => 'Campaign Name', 'required' => false))
                ->end()
                ->end()
        ;

        if (!$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper->with('Management', array('collapsed' => true))
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
            ;
        }

        $formMapper
                ->tab('Security')
                ->with('Security', array('collapsed' => true))
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

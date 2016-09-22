<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\SettingsBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class SettingsAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
                ->with('Website Settings and Global Variables', array('collapsed' => false))
                ->add('websiteTitle', null, array('attr' => array(), 'label' => 'Website Title', 'required' => true))
                ->add('itemsPerPage', null, array('attr' => array(), 'label' => 'Pagination item per Page', 'required' => true))
                ->add('blogItemsPerPage', null, array('attr' => array(), 'label' => 'Pagination item per Blog Page', 'required' => true))
                ->add('usersPerPage', null, array('attr' => array(), 'label' => 'Pagination item per User List Page', 'required' => true))
                ->add('metaDescription', null, array('attr' => array(), 'label' => 'Default Meta Description', 'required' => false))
                ->add('metaKeywords', null, array('attr' => array(), 'label' => 'Default Meta Keywords', 'required' => false))
                ->add('fromTitle', null, array('attr' => array(), 'label' => 'Meta Description Owner', 'required' => false))
                ->add('websiteAuthor', null, array('attr' => array(), 'label' => 'Website Default Author', 'required' => false))
                ->add('websiteTwitter', null, array('attr' => array(), 'label' => 'Website Twitter User', 'required' => false))
                ->add('useWebsiteAuthor', null, array('attr' => array(), 'label' => 'Use Default Author', 'required' => false))
                ->add('googleAnalyticsId', null, array('attr' => array(), 'label' => 'Google Analytics Id', 'required' => false))
                ->add('enableGoogleAnalytics', null, array('attr' => array(), 'label' => 'Enable Google Analytics', 'required' => false))
                ->add('emailRecepient', null, array('attr' => array(), 'label' => 'Recepient Email', 'required' => true))
                ->add('emailSender', null, array('attr' => array(), 'label' => 'Senders Email', 'required' => false))
                ->add('isPublicProfilesAllowed', null, array('attr' => array(), 'label' => 'Allow Public Profile Listing', 'required' => false))
                ->add('activateHttpCache', null, array('attr' => array(), 'label' => 'Activate HTTP Cache', 'required' => false))
                ->add('activateSettings', null, array('attr' => array(), 'label' => 'Activate Settings', 'required' => false))
                ->setHelps(array(
                ))
                ->end()
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
                ->addIdentifier('websiteTitle')
                ->addIdentifier('itemsPerPage')
                ->addIdentifier('blogItemsPerPage')
                ->addIdentifier('usersPerPage')
                ->addIdentifier('metaDescription')
                ->addIdentifier('metaKeywords')
                ->addIdentifier('fromTitle')
                ->addIdentifier('websiteAuthor')
                ->addIdentifier('useWebsiteAuthor')
                ->addIdentifier('googleAnalyticsId')
                ->addIdentifier('enableGoogleAnalytics')
                ->addIdentifier('emailRecepient')
                ->addIdentifier('emailSender')
                ->addIdentifier('isPublicProfilesAllowed')
                ->addIdentifier('activateHttpCache')
                ->addIdentifier('activateSettings')
                ->add('_action', 'actions', array(
                    'actions' => array(
                        'edit' => array(
                            'template' => 'SettingsBundle:Admin:edit.html.twig',
                        ),
                    ),
                )
        );
    }
}

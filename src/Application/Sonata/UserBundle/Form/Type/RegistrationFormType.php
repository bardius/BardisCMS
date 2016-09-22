<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationFormType extends AbstractType
{
    private $campaignData;
    private $class;
    private $requestStack;

    /**
     * Construct form for RegistrationFormType.
     *
     * @param string       $class        The User class name
     * @param RequestStack $requestStack
     */
    public function __construct($class, RequestStack $requestStack)
    {
        $this->class = $class;
        $this->requestStack = $requestStack;
    }

    /**
     * Build form for RegistrationFormType.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getMasterRequest();
        $REQUEST_URI = $request->server->get('REQUEST_URI');

        // Getting the campaign identifier from url for KPI measurement
        $this->campaignData = null;
        $this->campaignData = explode('/track-campaign/', $REQUEST_URI);
        $this->campaignData = str_replace('/', '', end($this->campaignData));

        // Adding custom extra user fields for Registration Form
        $builder
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'translation_domain' => 'SonataUserBundle',
                'attr' => array(
                    'placeholder' => 'user@domain.com',
                    'minlength' => 2,
                    'maxlength' => 255,
                ),
                'required' => true,
            ))
            ->add('username', TextType::class, array(
                'label' => 'form.username',
                'translation_domain' => 'SonataUserBundle',
                'attr' => array(
                    'placeholder' => 'username',
                    'minlength' => 6,
                    'maxlength' => 20,
                ),
                'required' => true,
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'SonataUserBundle',
                    'error_bubbling' => false,
                ),
                'first_options' => array(
                    'label' => 'form.password',
                    'attr' => array(
                        'placeholder' => 'password',
                        'minlength' => 8,
                        'maxlength' => 255,
                    ),
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation',
                    'attr' => array(
                        'placeholder' => 'password',
                        'minlength' => 8,
                        'maxlength' => 255,
                    ),
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'form.firstname',
                'translation_domain' => 'SonataUserBundle',
                'attr' => array(
                    'placeholder' => 'Name',
                    'minlength' => 2,
                    'maxlength' => 64,
                ),
                'required' => true,
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'form.lastname',
                'translation_domain' => 'SonataUserBundle',
                'attr' => array(
                    'placeholder' => 'Surname',
                    'minlength' => 2,
                    'maxlength' => 64,
                ),
                'required' => true,
            ))
            ->add('campaign', HiddenType::class, array(
                'label' => 'form.campaignname',
                'translation_domain' => 'SonataUserBundle',
                'data' => $this->campaignData,
                'attr' => array(
                    'placeholder' => 'campaign',
                    'minlength' => 2,
                    'maxlength' => 255,
                ),
                'required' => false,
            ))
            ->add('termsAccepted', CheckboxType::class, array(
                'label' => 'form.tnc',
                'translation_domain' => 'SonataUserBundle',
                'required' => true,
            ))
            // Create user with no username and password (pre set email as both)
            //->remove('username')
            //->remove('plainPassword')
        ;
    }

    /**
     * Configure Options for RegistrationFormType.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'registration',
            'error_mapping' => array(
                'safePassword' => 'plainPassword',
            ),
        ));
    }

    /**
     * Define the name of the form to call it for rendering.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_user_custom_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}

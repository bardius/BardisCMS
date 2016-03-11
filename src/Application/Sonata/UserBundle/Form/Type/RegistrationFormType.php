<?php

/*
 * User Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class RegistrationFormType extends AbstractType {

    private $campaignData;
    private $class;
    private $requestStack;

    /**
     * @param string $class The User class name
     * @param RequestStack $requestStack
     */
    public function __construct($class, RequestStack $requestStack)
    {
        $this->class = $class;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $request = $this->requestStack->getMasterRequest();
        $REQUEST_URI = $request->server->get('REQUEST_URI');

        // Getting the campaign identifier from url for KPI metrics
        $this->campaignData = null;
        $this->campaignData = explode('/track-campaign/', $REQUEST_URI);
        $this->campaignData = str_replace("/", "", end($this->campaignData));

        // Adding custom extra user fields for Registration Form
        $builder
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
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array(
                    'translation_domain' => 'SonataUserBundle',
                    'error_bubbling' => false,
                ),
                'first_options' => array(
                    'label' => 'form.password'
                ),
                'second_options' => array(
                    'label' => 'form.password_confirmation'
                ),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('firstname', TextType::class, array(
                'label' => 'form.firstname',
                'translation_domain' => 'SonataUserBundle',
                'required' => true
            ))
            ->add('lastname', TextType::class, array(
                'label' => 'form.lastname',
                'translation_domain' => 'SonataUserBundle',
                'required' => true
            ))
            ->add('campaign', HiddenType::class, array(
                'label' => 'form.campaignname',
                'translation_domain' => 'SonataUserBundle',
                'data' => $this->campaignData,
                'required' => false
            ))
            ->add('termsAccepted', CheckboxType::class, array(
                'label' => 'form.tnc',
                'translation_domain' => 'SonataUserBundle',
                'required' => true
            ))
            // Create user with no username and password (pre set email as both)
            //->remove('username')
            //->remove('plainPassword')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'registration',
            'error_mapping' => array(
                'safePassword' => 'plainPassword',
            )
        ));
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix() {
        return 'sonata_user_custom_user_registration';
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}

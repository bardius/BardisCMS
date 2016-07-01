<?php

/*
 * Sonata User Bundle Overrides
 * This file is part of the BardisCMS.
 * Manage the extended Sonata User entity with extra information for the users
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\FormType;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;

use Application\Sonata\UserBundle\Entity\User;

class AccountPreferencesFormType extends AbstractType {

	private $class;
	private $container;

    /**
     * Construct form for AccountPreferencesFormType
     *
     * @param string $class The User class name
     * @param Container $container
     *
     */
	public function __construct($class, Container $container) {
		$this->class = $class;
		$this->container = $container;
	}

    /**
     * Build form for AccountPreferencesFormType
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
	public function buildForm(FormBuilderInterface $builder, array $options) {
        // Set up variable values
		$user = $this->container->get('security.context')->getToken()->getUser();

        // Load values from persisted User data
		$defaults = array(
            'language' => $user->getLanguage(),
            'currencyCode' => $user->getCurrencyCode(),
            'biography' => $user->getBiography(),
            'website' => $user->getWebsite(),
            'timezone' => $user->getTimezone(),
			'secretQuestion' => $user->getSecretQuestion(),
			'secretQuestionResponse' => $user->getSecretQuestionResponse()
		);

        // Adding custom extra user fields for Account Preferences Form
		$builder
            ->add('language', LanguageType::class, array(
                'preferred_choices' => array(
                    User::LANGUAGE_EN
                ),
                'label' => 'form.language',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['language'],
                'required' => true
            ))
			->add('currencyCode', ChoiceType::class, array(
                'choices' => array(
                    User::CURRENCY_POUND    => 'GBP',
                    User::CURRENCY_EURO     => 'EUR',
                    User::CURRENCY_USD      => 'USD'
                ),
                'preferred_choices' => array(
                    User::CURRENCY_POUND
                ),
                'label' => 'form.currencyCode',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['currencyCode'],
                'required' => true,
                'expanded' => true,
                'multiple' => false
            ))
            ->add('biography', TextareaType::class, array(
                'label' => 'form.biography',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['biography'],
                "attr" => [
                    'placeholder' => "Profile details",
                    'maxlength' => 1000
                ],
                'required' => false
            ))
            ->add('website', UrlType::class, array(
                'label' => 'form.website',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['website'],
                "attr" => [
                    'placeholder' => "http://www.domain.com",
                    'minlength' => 2,
                    'maxlength' => 64
                ],
                'required' => false
            ))
            ->add('timezone', TimezoneType::class, array(
                'preferred_choices' => array(
                    User::TIMEZONE_LONDON
                ),
                'label' => 'form.timezone',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['timezone'],
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
                'data' => $defaults['secretQuestion'],
                'required' => false
            ))
            ->add('secretQuestionResponse', TextType::class, array(
                'label' => 'form.secretQuestionResponse',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['secretQuestionResponse'],
                "attr" => [
                    'placeholder' => "The secret question answer",
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'required' => false
            ))
            ->add('secretQuestionResponse', TextType::class, array(
                'label' => 'form.secretQuestionResponse',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['secretQuestionResponse'],
                "attr" => [
                    'placeholder' => "The secret question answer",
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'required' => false
            ))
        ;
	}

    /**
     * Configure Options for AccountPreferencesFormType
     * with error mapping for non field errors
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile_account_preferences_edit',
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'sonata_user_account_preferences';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }

}

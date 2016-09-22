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

use Application\Sonata\UserBundle\Entity\User;
use Sonata\MediaBundle\Form\Type\MediaType;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountMediaFormType extends AbstractType
{
    private $class;
    private $container;

    /**
     * Construct form for AccountMediaFormType.
     *
     * @param string    $class     The User class name
     * @param Container $container
     */
    public function __construct($class, Container $container)
    {
        $this->class = $class;
        $this->container = $container;
    }

    /**
     * Build form for AccountMediaFormType.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Set up variable values
        $user = $this->container->get('security.context')->getToken()->getUser();

        // Load values from persisted User data
        $defaults = array(
            'userAvatar' => $user->getUserAvatar(),
            'userHeroImage' => $user->getUserHeroImage(),
        );

        // Adding custom extra user fields for Account Media Form
        $builder
            ->add('userAvatar', MediaType::class, array(
                'label' => 'form.userAvatar',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['userAvatar'],
                'data_class' => 'Application\Sonata\MediaBundle\Entity\Media',
                'provider' => 'sonata.media.provider.image',
                'context' => 'user_avatar',
                'attr' => array(
                    'class' => 'imagefield',
                ),
                'required' => false,
            ))
            ->add('userHeroImage', MediaType::class, array(
                'label' => 'form.userHeroImage',
                'translation_domain' => 'SonataUserBundle',
                'data' => $defaults['userHeroImage'],
                'data_class' => 'Application\Sonata\MediaBundle\Entity\Media',
                'provider' => 'sonata.media.provider.image',
                'context' => 'user_hero',
                'attr' => array(
                    'class' => 'imagefield',
                ),
                'required' => false,
            ))
        ;
    }

    /**
     * Configure Options for AccountMediaFormType
     * with error mapping for non field errors.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'profile_account_media_edit',
        ));
    }

    /**
     * Define the name of the form to call it for rendering.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'sonata_user_account_media';
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

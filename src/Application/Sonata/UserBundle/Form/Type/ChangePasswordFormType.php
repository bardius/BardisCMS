<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ChangePasswordFormType extends AbstractType {

    private $class;
    private $container;

    /**
     * @param string $class The User class name
     * @param Container $container
     */
    public function __construct($class, $container) {
        $this->class = $class;
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (class_exists('Symfony\Component\Security\Core\Validator\Constraints\UserPassword')) {
            $constraint = new UserPassword();
        } else {
            // Symfony 2.1 support with the old constraint class
            $constraint = new OldUserPassword();
        }

        $builder
            ->add('current_password', 'password', array(
                'label' => 'form.current_password',
                'translation_domain' => 'SonataUserBundle',
                'mapped' => false,
                'constraints' => $constraint,
            ))
            ->add('new', 'repeated', array(
                'type' => 'password',
                'options' => array(
                    'translation_domain' => 'SonataUserBundle'
                ),
                'first_options' => array(
                    'label' => 'form.new_password'
                ),
                'second_options' => array(
                    'label' => 'form.new_password_confirmation'
                ),
                'invalid_message' => 'sonata_user.password.mismatch',
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'change_password',
        ));
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix() {
        return 'sonata_user_change_password';
    }

    public function getExtendedType()
    {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}

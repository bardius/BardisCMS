<?php

/*
 * Comment Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\CommentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use BardisCMS\CommentBundle\Form\EventListener\SanitizeFieldSubscriber;

use Symfony\Component\DependencyInjection\Container;
use FOS\UserBundle\Model\UserInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentFormType extends AbstractType {

    private $class;

    /**
     * Construct form for CommentFormType
     *
     * @param string $class The Comment class name
     * @param Container $container
     *
     */
    public function __construct($class, Container $container) {
        $this->class = $class;
        $this->container = $container;
    }

    /**
     * Build form for CommentFormType
     *
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        // Load values from persisted User data
        $defaults = array(
            'username' => null
        );

        // Get the logged user if any
        $logged_user = $this->container->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($logged_user) && $logged_user instanceof UserInterface) {
            $defaults['username'] = $logged_user->getUsername();
        }

        // TODO: make the username readonly and only allow registered users to comment

        $builder
            ->add('title', TextType::class, array(
                'label' => 'comment.form.title.label',
                'translation_domain' => 'BardisCMSCommentBundle',
                "attr" => [
                    'placeholder' => "comment.form.title.placeholder",
                    'minlength' => 2,
                    'maxlength' => 255
                ],
                'required' => true
            ))
            ->add('username', TextType::class, array(
                'label' => 'comment.form.username.label',
                'translation_domain' => 'BardisCMSCommentBundle',
                'data' => $defaults['username'],
                'attr' => [
                    'placeholder' => 'comment.form.username.placeholder',
                    'minlength' => 6,
                    'maxLength' => 20,
                    'readonly' => $defaults['username'] ? true : false
                ],
                'required' => true
            ))
            ->add('comment', TextareaType::class, array(
                'label' => 'comment.form.comment.label',
                'translation_domain' => 'BardisCMSCommentBundle',
                'attr' => [
                    'placeholder' => 'comment.form.comment.placeholder',
                    'maxLength' => 1000,
                    'cols' => 70,
                    'rows' => 8
                ],
                'required' => true
            ))
            ->add('bottrap', TextType::class, array(
                'label' => 'comment.form.bottrap.label',
                'translation_domain' => 'BardisCMSCommentBundle',
                'attr' => [
                    'placeholder' => '',
                    'maxLength' => 1
                ],
                'required' => false,
            ))
        ;

        // Sanitize data to avoid XSS attacks
        $builder->addEventSubscriber(new SanitizeFieldSubscriber());
    }

    /**
     * Configure Options for CommentFormType
     * with error mapping for non field errors
     *
     * @param OptionsResolver $resolver
     *
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention' => 'comment_form_submit'
        ));
    }

    /**
     * Define the name of the form to call it for rendering
     *
     * @return string
     *
     */
    public function getBlockPrefix() {
        return 'commentform';
    }

    public function getName() {
        return $this->getBlockPrefix();
    }

    public function getExtendedType() {
        return method_exists(AbstractType::class, 'getBlockPrefix') ? FormType::class : 'form';
    }
}

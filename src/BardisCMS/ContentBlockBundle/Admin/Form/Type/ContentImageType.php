<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\ContentBlockBundle\Admin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentImageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {
        $formBuilder
                ->add('imageOrder', 'integer', array('attr' => array('class' => 'imageOrderField'), 'label' => 'Image Ordering', 'required' => true))
                ->add('imageFile', 'sonata_media_type', array('provider' => 'sonata.media.provider.image', 'context' => 'default', 'attr' => array('class' => 'imagefield'), 'label' => 'Image File', 'required' => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $optionsNormalizer = function (Options $options, $value) {
            $value = 'BardisCMS\ContentBlockBundle\Entity\ContentImage';

            return $value;
        };

        $resolver->setNormalizer('data_class', $optionsNormalizer);
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix()
    {
        return 'contentimage';
    }
}

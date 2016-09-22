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

use Symfony\Component\Form\Extension\Core\EventListener\ResizeFormListener;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContentImageCollectionType extends CollectionType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['allow_add'] && $options['prototype']) {
            $prototype = $builder->create(
                $options['prototype_name'],
                $options['entry_type'],
                array_replace(
                    array(
                        'label' => $options['prototype_name'].'label__',
                    ),
                    $options['entry_options'], array(
                        'data' => $options['prototype_data'],
                    )
                )
            );
            $builder->setAttribute('prototype', $prototype->getForm());
        }

        $resizeListener = new ResizeFormListener(
            $options['entry_type'],
            $options['entry_options'],
            $options['allow_add'],
            $options['allow_delete'],
            $options['delete_empty']
        );

        $builder->addEventSubscriber($resizeListener);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $entryOptionsNormalizer = function (Options $options, $value) {
            $value['block_name'] = 'entry';

            return $value;
        };

        $optionsNormalizer = function (Options $options, $value) use ($entryOptionsNormalizer) {
            if (null !== $value) {
                @trigger_error('The form option "options" is deprecated since version 2.8 and will be removed in 3.0. Use "entry_options" instead.', E_USER_DEPRECATED);
            }

            return $entryOptionsNormalizer($options, $value);
        };

        $typeNormalizer = function (Options $options, $value) {
            if (null !== $value) {
                @trigger_error('The form option "type" is deprecated since version 2.8 and will be removed in 3.0. Use "entry_type" instead.', E_USER_DEPRECATED);
            }

            return $value;
        };

        $entryType = function (Options $options) {
            if (null !== $options['type']) {
                return $options['type'];
            }

            return __NAMESPACE__.'\TextType';
        };

        $entryOptions = function (Options $options) {
            if (1 === count($options['options']) && isset($options['block_name'])) {
                return array();
            }

            return $options['options'];
        };

        $resolver->setDefaults(array(
            'allow_add' => true,
            'allow_delete' => true,
            'prototype' => true,
            'prototype_data' => null,
            'prototype_name' => 'block__name__',
            'type' => 'contentimage',
            'options' => array(),
            'entry_type' => $entryType,
            'entry_options' => $entryOptions,
            'delete_empty' => false,
            'required' => false,
        ));

        $resolver->setNormalizer('type', $typeNormalizer);
        $resolver->setNormalizer('options', $optionsNormalizer);
        $resolver->setNormalizer('entry_options', $entryOptionsNormalizer);
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    // Define the name of the form to call it for rendering
    public function getBlockPrefix()
    {
        return 'contentimagecollection';
    }
}

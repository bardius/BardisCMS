<?php

/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\ContentBlockBundle\Admin\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityManager;

class ContentGlobalBlockType extends AbstractType {

    private $entityManager;

    public function __construct(EntityManager $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options) {
        $formBuilder
                ->add('contentblock', 'entity', array(
                    'auto_initialize' => false,
                    'class' => 'BardisCMS\ContentBlockBundle\Entity\ContentBlock',
                    'query_builder' => $this->getGlobalBlocksQueryBuilder(),
                    'property' => 'title',
                    'expanded' => false,
                    'multiple' => false,
                    'attr' => array(
                        'class' => 'autoCompleteItems autoCompleteGlobalContentBlocks',
                        'data-sonata-select2' => 'false'
                    ),
                    'label' => 'Select Content Block',
                    'required' => true
                ))
        ;
    }

    // Function to retrieve the content blocks that are globally available
    public function getGlobalBlocksQueryBuilder() {

        // Initalize the query builder variables
        $qb = $this->entityManager->createQueryBuilder();
        $availability = 'global';

        // The query to get all global content blocks
        $qb->select('DISTINCT b')
                ->from('ContentBlockBundle:ContentBlock', 'b')
                ->where(
                        $qb->expr()->eq('b.availability', ':availability')
                )
                ->orderBy('b.title', 'ASC')
                ->setParameter('availability', $availability)
        ;

        return $qb;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $optionsNormalizer = function (Options $options, $value) {
            $value = 'BardisCMS\ContentBlockBundle\Entity\ContentGlobalBlock';

            return $value;
        };

        $resolver->setNormalizers(array(
            'data_class' => $optionsNormalizer,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName() {
        return 'contentglobalblock';
    }

}

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
use Sonata\AdminBundle\Form\EventListener\ResizeFormListener;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use BardisCMS\ContentBlockBundle\Admin\Form\EventListener\AddIdFieldSubscriber;
use BardisCMS\ContentBlockBundle\Entity\ContentBlock;
use BardisCMS\ContentBlockBundle\Admin\Form\EventListener\AddContentTypeFieldSubscriber;

class ContentBlockType extends AbstractType
{
    
    private $contentTypes;
    private $contentsizes;
    private $mediasizes;

    public function __construct(array $contentTypes, array $contentsizes, array $mediasizes)
    {
        $this->contentTypes     = $contentTypes;
        $this->contentsizes     = $contentsizes;
        $this->mediasizes       = $mediasizes;
    }
    
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $formBuilder, array $options)
    {             
        $subscriber = new AddIdFieldSubscriber($formBuilder->getFormFactory());
        $formBuilder->addEventSubscriber($subscriber);
		
        $subscriber = new AddContentTypeFieldSubscriber($formBuilder->getFormFactory(), $this->mediasizes);
        $formBuilder->addEventSubscriber($subscriber);
		
	$contentTypeChoices = $this->contentTypes;
        reset($contentTypeChoices);
        $prefContentTypeChoice = key($contentTypeChoices);
		
	$sizeclassChoices = $this->contentsizes;
        reset($sizeclassChoices);
        $prefSizeclassChoice = key($sizeclassChoices);
                
        $formBuilder
            ->add('title', 'text', array('label' => 'Content Block Title', 'required' => true))
            ->add('publishedState', 'choice', array('choices'   => array('0' => 'Unpublished', '1' => 'Published'), 'preferred_choices' => array('1'), 'label' => 'Publish State', 'required' => true))
            //->add('availability', 'choice', array('choices'   => array('page' => 'One Page Only', 'global' => 'All Pages'),'preferred_choices' => array('0'), 'label' => 'Available to', 'required' => true))
            ->add('showTitle', 'choice', array('choices'   => array('0' => 'Hide Title', '1' => 'Show Title'), 'preferred_choices' => array('1'), 'label' => 'Title Display State', 'required' => true))
            ->add('ordering', 'hidden', array('attr' => array('class' => 'orderField'), 'label' => 'Content Block Ordering', 'required' => true))
            ->add('className', 'text', array('label' => 'CSS Class', 'required' => false))
            ->add('idName', 'text', array('label' => 'CSS Id', 'required' => false))
            ->add('sizeClass', 'choice', array('choices' => $sizeclassChoices, 'preferred_choices' => array($prefContentTypeChoice), 'label' => 'Content Block Width', 'required' => true))
            ->add('contentType', 'choice', array('choices' => $contentTypeChoices,'preferred_choices' => array($prefSizeclassChoice), 'label' => 'Content Type', 'required' => true))
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {   
        $optionsNormalizer = function (Options $options, $value) {
            $value = 'BardisCMS\ContentBlockBundle\Entity\ContentBlock';

            return $value;
        };

        $resolver->setNormalizers(array(
            'data_class' => $optionsNormalizer,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'contentblock';
    }

}
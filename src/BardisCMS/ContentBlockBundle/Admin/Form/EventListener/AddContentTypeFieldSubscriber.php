<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\ContentBlockBundle\Admin\Form\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

class AddContentTypeFieldSubscriber implements EventSubscriberInterface
{
    private $factory;

    public function __construct(FormFactoryInterface $factory, array $mediasizes)
    {
        $this->factory = $factory;
        $this->mediasizes = $mediasizes;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        // During form creation setData() is called with null as an argument
        // by the FormBuilder constructor. We're only concerned with when
        // setData is called with an actual Entity object in it (whether new,
        // or fetched with Doctrine). This if statement let's us skip right
        // over the null condition.
        if (null === $data) {
            return;
        }

        $mediaSizeChoices = $this->mediasizes;
        reset($mediaSizeChoices);
        $prefMediaSizeChoice = key($mediaSizeChoices);

        // check the content block object type and present the required field to enter contents
        switch ($data->getContenttype()) {
            case 'html':
                $form->add($this->factory->createNamed('htmlText', 'textarea', null, array('auto_initialize' => false, 'attr' => array('class' => 'tinymce', 'data-theme' => 'advanced'), 'label' => 'Text - HTML Contents', 'required' => false)));
                break;
            case 'image':
                //$form->add($this->factory->createNamed('imageFile', 'sonata_media_type', null, array('auto_initialize' => false, 'provider' => 'sonata.media.provider.image', 'context' => 'default', 'attr' => array( 'class' => 'imagefield'), 'label' => 'Image File', 'required' => false)));
                $form->add($this->factory->createNamed('imageFiles', 'contentimagecollection', null, array('auto_initialize' => false, 'label' => 'Image Files')));
                $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('auto_initialize' => false, 'choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'file':
                $form->add($this->factory->createNamed('fileFile', 'sonata_media_type', null, array('auto_initialize' => false, 'provider' => 'sonata.media.provider.file', 'context' => 'default', 'attr' => array('class' => 'filefield'), 'label' => 'Attachment File', 'required' => false)));
                break;
            case 'youtube':
                $form->add($this->factory->createNamed('youtube', 'sonata_media_type', null, array('auto_initialize' => false, 'provider' => 'sonata.media.provider.youtube', 'context' => 'default', 'attr' => array('class' => 'videofield'), 'label' => 'Youtube Video Id', 'required' => false)));
                $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('auto_initialize' => false, 'choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'vimeo':
                $form->add($this->factory->createNamed('vimeo', 'sonata_media_type', null, array('auto_initialize' => false, 'provider' => 'sonata.media.provider.vimeo', 'context' => 'default', 'attr' => array('class' => 'videofield'), 'label' => 'Vimeo Video Id', 'required' => false)));
                $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('auto_initialize' => false, 'choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'slide':
                $form->add($this->factory->createNamed('slide', 'contentslide', null, array('auto_initialize' => false, 'label' => 'Slide Contents')));
                break;
            case 'globalblock':
                $form->add($this->factory->createNamed('globalblock', 'contentglobalblock', null, array('auto_initialize' => false, 'label' => 'Global Block Contents')));
                break;
            default:
        }
    }
}

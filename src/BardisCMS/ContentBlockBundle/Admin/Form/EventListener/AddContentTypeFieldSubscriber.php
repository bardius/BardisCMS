<?php
/*
 * ContentBlock Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\ContentBlockBundle\Admin\Form\EventListener;


use Symfony\Component\Form\Event\DataEvent;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Form\FormEvents;

class AddContentTypeFieldSubscriber implements EventSubscriberInterface
{
   private $factory;

    public function __construct(FormFactoryInterface $factory, array $mediasizes)
    {
        $this->factory      = $factory;
        $this->mediasizes   = $mediasizes;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that we want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(DataEvent $event)
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
		
        // check the content block object type and presend the required field to ender contents
        switch ($data->getContenttype()) {
            case 'html':
                    $form->add($this->factory->createNamed('htmlText', 'textarea', null, array('attr' => array( 'class' => 'tinymce', 'data-theme' => 'advanced'), 'label' => 'Text - HTML Contents', 'required' => false)));
                break;
            case 'image':
                    //$form->add($this->factory->createNamed('imageFile', 'sonata_media_type', null, array( 'provider' => 'sonata.media.provider.image', 'context' => 'default', 'attr' => array( 'class' => 'imagefield'), 'label' => 'Image File', 'required' => false)));
                    $form->add($this->factory->createNamed('imageFiles','contentimagecollection', null, array('label' => 'Image Files')));
                    $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'file':
                    $form->add($this->factory->createNamed('fileFile', 'sonata_media_type', null, array( 'provider' => 'sonata.media.provider.file', 'context' => 'default', 'attr' => array( 'class' => 'filefield'), 'label' => 'Attachment File', 'required' => false)));
                break;
            case 'youtube':
                    $form->add($this->factory->createNamed('youtube', 'sonata_media_type', null, array('provider' => 'sonata.media.provider.youtube', 'context' => 'default', 'attr' => array( 'class' => 'videofield'), 'label' => 'Youtube Video Id', 'required' => false)));
                    $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'vimeo':
                    $form->add($this->factory->createNamed('vimeo', 'sonata_media_type', null, array( 'provider' => 'sonata.media.provider.vimeo', 'context' => 'default', 'attr' => array( 'class' => 'videofield'), 'label' => 'Vimeo Video Id', 'required' => false)));
                    $form->add($this->factory->createNamed('mediaSize', 'choice', null, array('choices' => $mediaSizeChoices, 'preferred_choices' => array($prefMediaSizeChoice), 'label' => 'Media Size', 'required' => true)));
                break;
            case 'slide':
                    $form->add($this->factory->createNamed('slide', 'contentslide', null, array('label' => 'Slide Contents')));
                break;
            default:
        }
    }
}

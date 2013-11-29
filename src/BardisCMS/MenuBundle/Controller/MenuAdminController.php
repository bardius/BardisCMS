<?php
/*
 * Menu Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */
namespace BardisCMS\MenuBundle\Controller;

use BardisCMS\MenuBundle\Entity\Menu;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class MenuAdminController extends Controller
{
    
    public function duplicateAction($id = null)
    {
        // the key used to lookup the template
        $templateKey = 'edit';
        
        $id = $this->get('request')->get($this->admin->getIdParameter());

        $clonedObject = $this->admin->getObject($id);
        $clonedObject->setTitle($clonedObject->getTitle().' Clone');
        
        $object = $this->admin->getNewInstance();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the Item with id : %s', $id));
        }

        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($clonedObject);
        
        $page = $form->getData()->getPage();        
        unset($page);     
        
        $externalUrl = $form->getData()->getExternalUrl();        
        unset($externalUrl);      
        
        $menuUrlExtras = $form->getData()->getMenuUrlExtras();        
        unset($menuUrlExtras);      

        if ($this->get('request')->getMethod() == 'POST') {
            $form->bind($this->get('request'));

            $isFormValid = $form->isValid();

            // persist if the form was valid and if in preview mode the preview was approved
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->get('session')->setFlash('sonata_flash_success','flash_create_success');
                // redirect to edit mode
                return $this->redirectTo($object);
            }

            // show an error message if the form failed validation
            if (!$isFormValid) {
                $this->get('session')->setFlash('sonata_flash_error', 'flash_create_error');
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
            }
        }

        $view = $form->createView();

        // set the theme for the current Admin Form
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate($templateKey), array(
            'action' => 'create',
            'form'   => $view,
            'object' => $object,
        ));
    }
}
<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Model\UserInterface;

/**
 * Controller managing the user profile
 *
 * @author Christophe Coevoet <stof@notk.org>
 */
class ProfileController extends Controller
{
    private $page;
    private $enableHTTPCache;

    /**
     * Show the user
     */
    public function showAction($alias, $userName = null)
    {
        // TODO: Create the fixtures for the Page Bundle user-profile page
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias($alias);

        if (!$this->page) {
            return $this->render404Page();
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        // Check if mobile content should be served
        $serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Get the logged user
        $logged_user = $this->container->get('sonata_user.services.helpers')->getLoggedUser();

        // Get the details of the requested user
        $user_details_to_show = array(
            'page_username' => $userName,
            'logged_username' => '',
            'page_user' => $this->container->get('sonata_user.services.helpers')->getUserByUsername($userName)
        );

        if (!is_object($logged_user) || !$logged_user instanceof UserInterface) {
            // Public profile
            $user_details_to_show['public_profile'] = true;
        }
        else{
            // Private profile
            $user_details_to_show['public_profile'] = false;
            $user_details_to_show['logged_username'] = $logged_user->getUsername();
        }

        return $this->container->get('templating')->renderResponse('SonataUserBundle:Profile:show.html.twig', array(
                'page' => $this->page,
                'mobile' => $serveMobile,
                'user_details_to_show' => $user_details_to_show
            )
        );
    }

    /**
     * Edit the user
     */
    public function editAction()
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        // TODO: Create the fixtures for the Page Bundle user-profile/edit-details page
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("user-profile/edit-details");

        if (!$this->page) {
            return $this->render404Page();
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        // Check if mobile content should be served
        $serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Password Change Form
        $passwordForm = $this->container->get('fos_user.change_password.form');
        $passwordFormHandler = $this->container->get('fos_user.change_password.form.handler');

        $passwordProcess = $passwordFormHandler->process($user);
        if ($passwordProcess) {
            $this->addFlash('fos_user_success', 'change_password.flash.success');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        // Generic Details Form
        $genericDetailsForm = $this->container->get('sonata_user.generic_details.form');
        $genericDetailsFormHandler = $this->container->get('sonata_user.generic_details.form.handler');

        $genericDetailsProcess = $genericDetailsFormHandler->process($user);
        if ($genericDetailsProcess) {
            $this->addFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        // Contact Details Form
        $contactDetailsForm = $this->container->get('sonata_user.contact_details.form');
        $contactDetailsFormHandler = $this->container->get('sonata_user.contact_details.form.handler');

        $contactDetailsProcess = $contactDetailsFormHandler->process($user);
        if ($contactDetailsProcess) {
            $this->addFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        // Account Preferences Form
        $accountPreferencesForm = $this->container->get('sonata_user.account_preferences.form');
        $accountPreferencesFormHandler = $this->container->get('sonata_user.account_preferences.form.handler');

        $accountPreferencesProcess = $accountPreferencesFormHandler->process($user);
        if ($accountPreferencesProcess) {
            $this->addFlash('fos_user_success', 'profile.flash.updated');

            return new RedirectResponse($this->getRedirectionUrl($user));
        }

        return $this->container->get('templating')->renderResponse('SonataUserBundle:Profile:edit.html.' . $this->container->getParameter('fos_user.template.engine'), array(
                'passwordForm' => $passwordForm->createView(),
                'genericDetailsForm' => $genericDetailsForm->createView(),
                'contactDetailsForm' => $contactDetailsForm->createView(),
                'accountPreferencesForm' => $accountPreferencesForm->createView(),
                'page' => $this->page,
                'mobile' => $serveMobile
            )
        );
    }

    /**
     * Generate the redirection url when editing is completed.
     *
     * @param \FOS\UserBundle\Model\UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->container->get('router')->generate('sonata_user_profile_edit');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->container->get('session')->getFlashBag()->set($action, $value);
    }

    // Render the 404 error page
    protected function render404Page() {

        // Get the page with alias 404
        $this->page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias('404');

        // Check if page exists
        if (!$this->page) {
            throw $this->createNotFoundException('No 404 error page exists. No page found for with alias 404. Page has id: ' . $this->page->getId());
        }

        // Set the website settings and metatags
        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $this->page))->setStatusCode(404);

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response);
        }

        return $response;
    }
}

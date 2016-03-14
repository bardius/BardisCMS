<?php

/*
 * This file is part of the Sonata project.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Controller;

use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use BardisCMS\PageBundle\Entity\Page as Page;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ChangePasswordFOSUser1Controller.
 *
 * This class is inspired from the FOS Change Password Controller
 *
 *
 * @author  Hugo Briand <briand@ekino.com>
 */
class ChangePasswordFOSUser1Controller extends Controller
{
    protected $container;
    private $alias;
    private $id;
    private $extraParams;
    private $currentpage;
    private $totalpageitems;
    private $linkUrlParams;
    private $page;
    private $publishStates;
    private $userName;
    private $settings;
    private $serveMobile;
    private $userRole;
    private $enableHTTPCache;
    private $logged_username;
    private $logged_user;

    // Override the ContainerAware setContainer to accommodate the extra variables
    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;

        // Setting the scoped variables required for the rendering of the page
        $this->alias = null;
        $this->id = null;
        $this->extraParams = null;
        $this->currentpage = null;
        $this->totalpageitems = null;
        $this->linkUrlParams = null;
        $this->page = null;
        $this->userName = null;
        $this->logged_username = null;

        // Get the settings from setting bundle
        $this->settings = $this->get('bardiscms_settings.load_settings')->loadSettings();

        // Get the highest user role security permission
        $this->userRole = $this->get('sonata_user.services.helpers')->getLoggedUserHighestRole();

        // Check if mobile content should be served
        $this->serveMobile = $this->get('bardiscms_mobile_detect.device_detection')->testMobile();

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();

        // Set the publish statuses that are available for the user
        $this->publishStates = $this->get('bardiscms_page.services.helpers')->getAllowedPublishStates($this->userRole);

        // Get the logged user if any
        $this->logged_user = $this->get('sonata_user.services.helpers')->getLoggedUser();
        if (is_object($this->logged_user) && $this->logged_user instanceof UserInterface) {
            $this->logged_username = $this->logged_user->getUsername();
        }
    }

    /**
     * @return Response|RedirectResponse
     *
     * @throws AccessDeniedException
     */
    public function changePasswordAction()
    {
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            $this->createAccessDeniedException('This user does not have access to this section.');
        }

        $page = $this->getDoctrine()->getRepository('PageBundle:Page')->findOneByAlias("user/password-change");

        if (!$page) {
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_404);
        }

        $this->page = $page;
        $this->id = $this->page->getId();

        // Simple publishing ACL based on publish state and user Allowed Publish States
        $accessAllowedForUserRole = $this->get('bardiscms_page.services.helpers')->isUserAccessAllowedByRole(
            $this->page->getPublishState(),
            $this->publishStates
        );
        if(!$accessAllowedForUserRole){
            return $this->get('bardiscms_page.services.show_error_page')->errorPageAction(Page::ERROR_401);
        }

        $this->page = $this->get('bardiscms_settings.set_page_settings')->setPageSettings($this->page);

        $form = $this->get('fos_user.change_password.form');
        $formHandler = $this->get('fos_user.change_password.form.handler');

        $process = $formHandler->process($user);
        if ($process) {
            $this->setFlash('fos_user_success', 'change_password.flash.success');

            return $this->redirect($this->getRedirectionUrl($user));
        }

        $pageData = array(
            'page' => $this->page,
            'mobile' => $this->serveMobile,
            'logged_username' => $this->logged_username,
            'form' => $form->createView()
        );

        // Render login page
        $response = $this->render('SonataUserBundle:ChangePassword:changePassword.html.twig', $pageData);
        // $response = $this->container->get('templating')->renderResponse('SonataUserBundle:ChangePassword:changePassword.html.$this->container->getParameter('fos_user.template.engine'), $pageData);

        return $response;
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    protected function getRedirectionUrl(UserInterface $user)
    {
        return $this->generateUrl('sonata_user_profile_show');
    }

    /**
     * @param string $action
     * @param string $value
     */
    protected function setFlash($action, $value)
    {
        $this->get('session')->getFlashBag()->set($action, $value);
    }

    // Set a custom Cache-Control directives
    protected function setResponseCacheHeaders(Response $response) {

        $response->setPublic();
        $response->setLastModified($this->page->getDateLastModified());
        $response->setVary(array('Accept-Encoding', 'User-Agent'));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge(3600);

        return $response;
    }
}

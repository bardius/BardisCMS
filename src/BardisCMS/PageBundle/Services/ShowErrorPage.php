<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ShowErrorPage
{
    private $em;
    private $conn;
    private $container;
    private $enableHTTPCache;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->conn = $em->getConnection();
        $this->container = $container;

        // Set the flag for allowing HTTP cache
        $this->enableHTTPCache = $this->container->getParameter('kernel.environment') == 'prod' && $this->settings->getActivateHttpCache();
    }

    public function errorPageAction($statusCode = null){
        if($statusCode == null){
            $statusCode = "404";
        }

        // Get the page with alias the status code of the error eg. 404
        $page = $this->em->getRepository('PageBundle:Page')->findOneByAlias($statusCode);

        // Check if page exists
        if (!$page) {
            throw new NotFoundHttpException('No ' . $statusCode . ' error page exists. No page found for with alias ' . $statusCode . '.');
        }

        // Set the website settings and metatags
        $page = $this->container->get('bardiscms_settings.set_page_settings')->setPageSettings($page);

        $response = $this->render('PageBundle:Default:page.html.twig', array('page' => $page))->setStatusCode($statusCode);

        if ($this->enableHTTPCache) {
            $response = $this->setResponseCacheHeaders($response, $page->getDateLastModified());
        }

        return $response;
    }

    /**
     * Renders a view.
     *
     * @param string   $view       The view name
     * @param array    $parameters An array of parameters to pass to the view
     * @param Response $response   A response instance
     *
     * @return Response A Response instance
     */
    private function render($view, array $parameters = array(), Response $response = null)
    {
        if ($this->container->has('templating')) {
            return $this->container->get('templating')->renderResponse($view, $parameters, $response);
        }

        if (!$this->container->has('twig')) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available.');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($this->container->get('twig')->render($view, $parameters));

        return $response;
    }

    // set a custom Cache-Control directives
    protected function setResponseCacheHeaders(Response $response, \Datetime $dateLastModified) {

        $response->setPublic();
        $response->setLastModified($dateLastModified);
        $response->setVary(array('Accept-Encoding', 'User-Agent'));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge(3600);

        return $response;
    }
}

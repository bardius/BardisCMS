<?php

/*
 * PageBundle Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Services;

use Symfony\Component\HttpFoundation\Response;

class HttpCacheHeadersHandler {

    /**
     * Set custom HTTP Header Cache-Control directives
     *
     * To better understand the caching strategy read the resources
     * https://devcenter.heroku.com/articles/increasing-application-performance-with-http-cache-headers
     * https://developers.google.com/web/fundamentals/performance/optimizing-content-efficiency/http-caching?hl=en
     *
     * @param Response|null $response       The response that will be returned
     * @param \DateTime $dateLastModified   The last modified datetime of the page in CMS
     * @param string $eTagHash              The ETag Http Header
     * @param bool $isPrivate               If the response is public or private
     * @param int $sharedMaxAge             The SharedMaxAge/MaxAge of the response eg. 3600ms
     *
     * @return Response
     */
    public function setResponseCacheHeaders($response, \DateTime $dateLastModified , $eTagHash, $isPrivate = true, $sharedMaxAge = 3600) {
        if($response == null){
            $response = new Response();
        }

        $sharedMaxAge = 1;

        if($isPrivate){
            $response->setPrivate();
            $response->setMaxAge($sharedMaxAge);
            $response->headers->set('X-User-Context-Hash', $eTagHash);
            $response->setVary(array('Authorization', 'Accept-Encoding', 'User-Agent', 'Cookie', 'X-User-Context-Hash'));

            // To disallow proxies storing any cached response
            //$response->headers->addCacheControlDirective('no-store', true);
            //$response->headers->addCacheControlDirective('no-cache', true);
            //$response->headers->set('Pragma', 'no-cache');
            //$response->setMaxAge(0);
        }
        else {
            // Set Cache header to public to allow caching on reverse proxy servers
            $response->setPublic();
            $response->setSharedMaxAge($sharedMaxAge);
            $response->setMaxAge($sharedMaxAge);
            $response->headers->set('X-User-Context-Hash', $eTagHash);
            $response->setVary(array('Authorization', 'Accept-Encoding', 'User-Agent', 'X-User-Context-Hash'));
        }

        // Use instead of ETag if non authenticated pages exist for caching
        $response->setLastModified($dateLastModified);
        $response->setETag($eTagHash);

        // To enforce browsers to request server for revalidate each time
        //$response->headers->set('Expires', '-1');
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}

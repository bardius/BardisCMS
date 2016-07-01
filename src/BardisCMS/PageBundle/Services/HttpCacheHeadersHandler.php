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
     * https://www.keycdn.com/blog/http-cache-headers/
     *
     * @param Response|null $response       The response that will be returned
     * @param \DateTime $dateLastModified   The last modified datetime of the page in CMS
     * @param string $eTagHash              The ETag Http Header
     * @param bool $isPrivate               If the response is public or private
     * @param int $maxAge                   The SharedMaxAge/MaxAge of the response eg. 3600s
     * @param bool $disallowCache           If the response should never be cached
     *
     * @return Response
     */
    public function setResponseCacheHeaders($response, \DateTime $dateLastModified , $eTagHash, $isPrivate = true, $maxAge = 3600, $disallowCache = false) {
        if($response === null){
            $response = new Response();
        }

        if($isPrivate){
            $response->setPrivate();
            $response->setMaxAge(0);
            $response->headers->set('X-User-Context-Hash', $eTagHash);
            $response->setVary(array('Accept-Encoding', 'User-Agent', 'Cookie', 'X-User-Context-Hash'));
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->set('Expires', '-1');
        }
        else {
            // Set Cache header to public to allow caching on reverse proxy servers
            $response->setPublic();
            $response->setSharedMaxAge($maxAge);
            $response->headers->set('X-User-Context-Hash', $eTagHash);
            $response->setVary(array('Accept-Encoding', 'User-Agent', 'Cookie', 'X-User-Context-Hash'));
            $response->headers->addCacheControlDirective('no-cache', true);
        }

        // Use instead of ETag if non authenticated pages exist for caching
        $response->setETag($eTagHash);
        $response->setLastModified($dateLastModified);

        // To enforce browsers to request server for revalidate each time
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('proxy-revalidate', true);

        // To disallow proxies storing any cached response
        if($disallowCache){
            $response->setPrivate();
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->set('Pragma', 'no-cache');
            $response->setMaxAge(0);
            $response->headers->set('Expires', '-1');
        }

        return $response;
    }
}

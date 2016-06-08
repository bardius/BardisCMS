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
     * @param Response|null $response       The response that will be returned
     * @param \DateTime $dateLastModified   The last modified datetime of the page in CMS
     * @param bool $isPrivate               If the response is public or private
     * @param int $sharedMaxAge             The SharedMaxAge/MaxAge of the response eg. 3600ms
     *
     * @return Response
     */
    public function setResponseCacheHeaders($response, \DateTime $dateLastModified ,$isPrivate = true, $sharedMaxAge = 3600) {
        if($response == null){
            $response = new Response();
        }
        // TODO: use ETag based on username, alias and getDateLastModified to accommodate logged users properly
        // TODO: calculate the getDateLastModified properly based on the contents of  the page

        if($isPrivate){
            $response->headers->set('X-User-Context-Hash', '67890');
            $response->headers->set('ETag', '67890');
            $response->setVary(array('Accept-Encoding', 'User-Agent', 'X-User-Context-Hash', 'Cookie'));
        }
        else {
            $response->headers->set('X-User-Context-Hash', '12345');
            $response->headers->set('ETag', '12345');
            $response->setVary(array('Accept-Encoding', 'User-Agent', 'X-User-Context-Hash'));
        }

        // Set Cache header to public to allow caching on reverse proxy servers
        $response->setPublic();
        $response->setSharedMaxAge($sharedMaxAge);

        $response->setLastModified($dateLastModified);
        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }
}

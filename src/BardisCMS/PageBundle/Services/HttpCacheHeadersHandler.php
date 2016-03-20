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

    // Set custom HTTP Header Cache-Control directives

    /**
     * Set custom HTTP Header Cache-Control directives
     *
     * @param Response|null $response       The response that will be returned
     * @param \DateTime $dateLastModified   The last modified datetime of the page in CMS
     * @param bool $isPrivate               If the response is public or private
     * @param int $sharedMaxAge             The SharedMaxAge of the response eg. 3600ms
     *
     * @return Response
     */
    public function setResponseCacheHeaders($response, \DateTime $dateLastModified ,$isPrivate = true, $sharedMaxAge = 3600) {
        if($response == null){
            $response = new Response();
        }

        if($isPrivate){
            $response->setPrivate();
        }
        else {
            $response->setPublic();
        }

        $response->setLastModified($dateLastModified);
        $response->setVary(array('Accept-Encoding', 'User-Agent'));
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setSharedMaxAge($sharedMaxAge);

        return $response;
    }
}

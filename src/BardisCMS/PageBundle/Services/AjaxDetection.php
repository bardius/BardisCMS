<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\PageBundle\Services;

use Symfony\Component\HttpFoundation\RequestStack;

class AjaxDetection
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    // Test if request is AJAX
    public function isAjaxRequest()
    {
        $request = $this->requestStack->getCurrentRequest();

        return $request->isXmlHttpRequest();
    }
}

<?php

/*
 * This file is part of BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BardisCMS\MenuBundle\Voter;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Voter based on the uri.
 */
class RequestVoter implements VoterInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Checks whether an item is current.
     *
     * If the voter is not able to determine a result,
     * it should return null to let other voters do the job.
     *
     * @param ItemInterface $item
     *
     * @return bool|null
     */
    public function matchItem(ItemInterface $item)
    {
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $request = $this->container->get('request');

        if ($item->getUri() === $request->getRequestUri()) {
            return true;
        }

        if ($item->getExtra('routes') !== null && in_array($request->attributes->get('_route'), $item->getExtra('routes'), true)) {
            return true;
        }

        return null;
    }
}

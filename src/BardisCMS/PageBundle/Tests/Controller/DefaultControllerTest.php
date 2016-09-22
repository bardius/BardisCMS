<?php

/*
 * Page Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\PageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testAliasAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/index');

        $this->assertTrue($crawler->filter('html:contains("Homepage")')->count() > 0);
    }
}

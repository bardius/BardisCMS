<?php

/*
 * Blog Bundle
 * This file is part of the BardisCMS.
 *
 * (c) George Bardis <george@bardis.info>
 *
 */

namespace BardisCMS\BlogBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testAliasAction() {
		
		$alias = 'testalias';
	
		$client = static::createClient();

		$crawler = $client->request('GET', '/blog/testalias');

		$this->assertTrue($crawler->filter('html:contains("testalias")')->count() > 0);
    }
}

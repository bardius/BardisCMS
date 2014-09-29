<?php

namespace BardisCMS\PageBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase {

    public function testIndex() {
        $client = static::createClient();

        $crawler = $client->request('GET', '/hello/BardisCMS');

        $this->assertTrue($crawler->filter('html:contains("Hello BardisCMS")')->count() > 0);
    }

}

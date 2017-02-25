<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    public function testSingle()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/page');
    }

}

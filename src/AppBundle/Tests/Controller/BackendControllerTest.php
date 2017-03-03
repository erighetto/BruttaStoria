<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BackendControllerTest extends WebTestCase
{
    public function testNew_user()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new_user');
    }

    public function testNew_node()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new_node');
    }

    public function testNew_definition()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/new_definition');
    }

}

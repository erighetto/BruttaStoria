<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DictionaryControllerTest extends WebTestCase
{
    public function testList_nodes()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/list_nodes');
    }

    public function testSingle_node()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/single_node');
    }

    public function testVote_node()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vote_node');
    }

}

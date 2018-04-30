<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class EmailRouteTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/email');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("INDEX!")')->count());
    }

    public function testCategory()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/email/index');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("CATEGORY!")')->count());
    }

    public function testLabel()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/email/label/personal');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1:contains("LABEL!")')->count());
    }

    public function testCreate()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/email/new');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1.message:contains("NEW EMAIL CREATED!")')->count());
    }

    public function testForward()
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/email/forward');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
        $this->assertSame(1, $crawler->filter('h1.message:contains("NEW EMAIL CREATED!")')->count());
    }
}

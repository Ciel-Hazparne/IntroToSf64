<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testUserIndexPageIsSuccessful(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user');

        $this->assertResponseIsSuccessful(); // 200 OK
        $this->assertSelectorTextContains('h1', 'Liste des utilisateurs'); // Titre attendu
    }

    public function testUserListContainsFixtureUser(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/user');

        // prénom présent dans la fixture
        $this->assertSelectorTextContains('td', 'TestFirstUser');
    }
}

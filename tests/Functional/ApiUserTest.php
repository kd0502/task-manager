<?php
namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiUserTest extends WebTestCase
{
    public function testCreateUser(): void
    {
        $c = static::createClient();
        $c->request('POST', '/api/users', [], [], ['CONTENT_TYPE'=>'application/json'], json_encode([
            'name' => 'John', 'email' => 'john@example.com'
        ]));
        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($c->getResponse()->getContent());
    }
}

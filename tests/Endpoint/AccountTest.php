<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Tests\Endpoint;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \MicroApi\Endpoint\Account
 *
 * @internal
 */
class AccountTest extends WebTestCase
{
    /**
     * @dataProvider dataAdmin
     *
     * @covers \MicroApi\Endpoint\Account::admin
     */
    public function testAdmin(array $server, string $access, int $statusCode): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request(method: 'GET', uri: '/admin', server: $server, content: $access);
        $code = $client->getResponse()->getStatusCode();
        $routeName = $client->getRequest()->attributes->get('_route');

        $this->assertJson(strval($client->getResponse()->getContent()));
        $this->assertEquals($statusCode, $code);
        $this->assertEquals('api_account_admin', $routeName);
    }

    /**
     * @dataProvider dataManager
     *
     * @covers \MicroApi\Endpoint\Account::manager
     */
    public function testManager(array $server, int $statusCode)
    {
        self::ensureKernelShutdown();
        $client = static::createClient();
        $client->request(method: 'GET', uri: '/manager', server: $server);
        $code = $client->getResponse()->getStatusCode();
        $routeName = $client->getRequest()->attributes->get('_route');

        $this->assertJson(strval($client->getResponse()->getContent()));
        $this->assertEquals($statusCode, $code);
        $this->assertEquals('api_account_manager', $routeName);
    }

    /**
     * @dataProvider dataUser
     *
     * @covers \MicroApi\Endpoint\Account::user
     */
    public function testUser(array $server, int $statusCode): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request(method: 'GET', uri: '/user', server: $server);
        $json = $client->getResponse()->getContent() ?: '{}';
        $code = $client->getResponse()->getStatusCode();
        $routeName = $client->getRequest()->attributes->get('_route');

        $this->assertJson(strval($json));
        $this->assertEquals($statusCode, $code);
        $this->assertEquals('api_account_user', $routeName);
    }

    /**
     * @codeCoverageIgnore
     */
    public static function dataAdmin(): \Generator
    {
        yield [
            'server' => ['CONTENT_TYPE' => 'application/json'],
            'access' => json_encode(['username' => 'admin', 'password' => 'micro.api.admin']),
            'status_code' => 200,
        ];

        yield [
            'server' => [],
            'access' => '',
            'status_code' => 401,
        ];
    }

    public static function dataManager(): \Generator
    {
        yield [
            'server' => ['HTTP_Authorization' => 'Bearer token.manager'],
            'status_code' => 200,
        ];

        yield [
            'server' => [],
            'status_code' => 401,
        ];
    }

    /**
     * @codeCoverageIgnore
     */
    public static function dataUser(): \Generator
    {
        yield [
            'server' => ['PHP_AUTH_USER' => 'user', 'PHP_AUTH_PW' => 'micro.api.user'],
            'status_code' => 200,
        ];

        yield [
            'server' => [],
            'status_code' => 401,
        ];
    }
}

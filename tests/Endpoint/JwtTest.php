<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Tests\Endpoint;

use MicroApi\Security\Firebase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @covers \MicroApi\Endpoint\Jwt
 *
 * @internal
 */
class JwtTest extends WebTestCase
{
    public const USER = [
        'username' => 'manager.token',
        'password' => 'manager.password',
        'email' => 'manager.email@yandex.ru',
        'token' => 'manager.token',
        'roles' => [
            'ROLE_MANAGER',
        ],
    ];

    /**
     * @dataProvider dataItems
     *
     * @covers \MicroApi\Endpoint\Jwt::decode
     */
    public function testDecode(bool $bearer, array $server, int $statusCode): void
    {
        if ($bearer) {
            $payload = Firebase::payload(self::USER);
            $token = Firebase::encode($payload);
            $server['HTTP_Authorization'] .= $token;
        }

        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request(method: 'GET', uri: '/jwt/decode', server: $server);
        $routeName = $client->getRequest()->attributes->get('_route');
        $response = strval($client->getResponse()->getContent()) ?: '{}';
        $code = $client->getResponse()->getStatusCode();

        $this->assertJson($response);
        $this->assertEquals($statusCode, $code);
        $this->assertEquals('api_jwt_decode', $routeName);
    }

    public static function dataItems(): \Generator
    {
        yield [
            'bearer' => true,
            'server' => ['HTTP_Authorization' => 'Bearer '],
            'status_code' => 200,
        ];

        yield [
            'bearer' => false,
            'server' => ['HTTP_Authorization' => 'Bearer no-jwt-token'],
            'status_code' => 401,
        ];

        yield [
            'bearer' => false,
            'server' => [],
            'status_code' => 500,
        ];
    }
}

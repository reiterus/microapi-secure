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
 * @covers \MicroApi\Endpoint\Jwt
 *
 * @internal
 */
class JwtTest extends WebTestCase
{
    /**
     * @dataProvider dataItems
     *
     * @covers \MicroApi\Endpoint\Jwt::decode
     */
    public function testDecode(bool $bearer, array $server, int $statusCode): void
    {
        self::ensureKernelShutdown();
        $client = static::createClient();

        $client->request(method: 'GET', uri: '/jwt/decode');
        $routeName = $client->getRequest()->attributes->get('_route');
        $response = strval($client->getResponse()->getContent());

        if ($bearer) {
            $client->request(method: 'GET', uri: '/manager', server: ['HTTP_Authorization' => 'Bearer manager.token']);
            $data = json_decode(strval($client->getResponse()->getContent()), true);
            $token = is_array($data) ? $data['token'] : '';
            $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $token));
        }

        $code = $client->getResponse()->getStatusCode();

        $this->assertJson($response);
        $this->assertEquals($statusCode, $code);
        $this->assertEquals('api_jwt_decode', $routeName);
    }

    public static function dataItems(): \Generator
    {
        yield [
            'bearer' => true,
            'server' => [],
            'status_code' => 200,
        ];

        yield [
            'bearer' => false,
            'server' => ['HTTP_Authorization' => 'Bearer no-jwt-token'],
            'status_code' => 500,
        ];

        yield [
            'bearer' => false,
            'server' => [],
            'status_code' => 500,
        ];
    }
}

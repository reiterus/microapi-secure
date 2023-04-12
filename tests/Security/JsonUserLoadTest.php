<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Tests\Security;

use MicroApi\Security\JsonUserLoad;
use PHPUnit\Framework\TestCase;

/**
 * @covers \MicroApi\Security\JsonUserLoad
 *
 * @internal
 */
class JsonUserLoadTest extends TestCase
{
    private JsonUserLoad $userLoad;

    public function __construct(string $name)
    {
        $path = dirname(__DIR__, 2).'/config/users.json';
        $json = file_get_contents($path);
        $users = (array) json_decode(strval($json), true);
        $this->userLoad = new JsonUserLoad($users);
        parent::__construct($name);
    }

    public function testByCredentials(): void
    {
        $user = $this->userLoad->byCredentials('user', 'user.password');
        $this->assertIsArray($user);
        $this->assertArrayHasKey('username', $user);
        $this->assertArrayHasKey('password', $user);
        $this->assertEquals('user', $user['username']);
        $this->assertEquals('user.password', $user['password']);
    }

    /**
     * @dataProvider dataByItems
     */
    public function testByMethod(string $method, string $key, string $value): void
    {
        $user = $this->userLoad->{$method}($value);
        $this->assertIsArray($user);
        $this->assertArrayHasKey($key, $user);
        $this->assertEquals($value, $user[$key]);
    }

    public static function dataByItems(): \Generator
    {
        yield [
            'method' => 'byToken',
            'key' => 'token',
            'value' => 'user.token',
        ];

        yield [
            'method' => 'byUsername',
            'key' => 'username',
            'value' => 'user',
        ];

        yield [
            'method' => 'byEmail',
            'key' => 'email',
            'value' => 'user.email@yandex.ru',
        ];
    }
}

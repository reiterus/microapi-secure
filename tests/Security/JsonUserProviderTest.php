<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Tests\Security;

use MicroApi\Security\JsonUser;
use MicroApi\Security\JsonUserLoad;
use MicroApi\Security\JsonUserProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @covers \MicroApi\Security\JsonUserProvider
 *
 * @internal
 */
class JsonUserProviderTest extends TestCase
{
    private JsonUserProvider $userProvider;

    public function __construct(string $name)
    {
        $path = dirname(__DIR__, 2).'/config/users.json';
        $json = file_get_contents($path);
        $users = (array) json_decode(strval($json), true);
        $userLoad = new JsonUserLoad($users);
        $this->userProvider = new JsonUserProvider($userLoad);
        parent::__construct($name);
    }

    /**
     * @covers \MicroApi\Security\JsonUserProvider::loadUserByIdentifier
     *
     * @dataProvider dataIdentifiers
     */
    public function testLoadUserByIdentifier(string $identifier): void
    {
        if ('unknown' == $identifier) {
            $this->expectException(UserNotFoundException::class);
        }
        $user = $this->userProvider->loadUserByIdentifier($identifier);
        $this->assertInstanceOf(UserInterface::class, $user);
    }

    /**
     * @covers \MicroApi\Security\JsonUserProvider::refreshUser
     *
     * @dataProvider dataIdentifiers
     */
    public function testRefreshUser(string $identifier): void
    {
        if ('unknown' == $identifier) {
            $this->expectException(UserNotFoundException::class);
        }
        $user = $this->userProvider->loadUserByIdentifier($identifier);
        $refreshed = $this->userProvider->refreshUser($user);
        $this->assertInstanceOf(UserInterface::class, $refreshed);
    }

    /**
     * @covers \MicroApi\Security\JsonUserProvider::supportsClass
     *
     * @dataProvider dataClasses
     */
    public function testSupportsClass(string $class, bool $result): void
    {
        $actual = $this->userProvider->supportsClass($class);
        $this->assertEquals($result, $actual);
    }

    public static function dataIdentifiers(): \Generator
    {
        yield ['admin'];

        yield ['manager'];

        yield ['user'];

        yield ['unknown'];
    }

    public static function dataClasses(): \Generator
    {
        yield [
            'class' => JsonUser::class,
            'result' => true,
        ];

        yield [
            'class' => JsonUserProvider::class,
            'result' => false,
        ];
    }
}

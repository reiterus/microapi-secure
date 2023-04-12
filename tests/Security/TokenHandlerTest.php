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
use MicroApi\Security\TokenHandler;
use MicroApi\Service\JwtToken;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

/**
 * @internal
 *
 * @coversNothing
 */
class TokenHandlerTest extends TestCase
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

    /**
     * @covers \MicroApi\Security\TokenHandler::getUserBadgeFrom
     *
     * @dataProvider dataTokens
     */
    public function testGetUserBadgeFrom(string $token, bool $exception): void
    {
        if ($exception) {
            $this->expectException(BadCredentialsException::class);
        }

        $handler = new TokenHandler($this->userLoad, new JwtToken());
        $actual = $handler->getUserBadgeFrom($token);
        $this->assertInstanceOf(UserBadge::class, $actual);
    }

    public static function dataTokens(): \Generator
    {
        yield [
            'token' => 'manager.token',
            'exception' => false,
        ];

        yield [
            'token' => 'wrong.token',
            'exception' => true,
        ];
    }
}

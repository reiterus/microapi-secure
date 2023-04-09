<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Firebase
{
    public const ALGO = 'HS256';

    public static function payload(array $data): array
    {
        $time = time();
        $expired = $time + self::lifetime();
        $sub = $data['email'] ?? 'unknown@gmail.com';
        unset($data['password'],$data['token']);

        return [
            'iat' => $time,
            'exp' => $expired,
            'sub' => $sub,
            'user' => $data,
        ];
    }

    public static function encode(array $payload): string
    {
        return JWT::encode($payload, self::key(), self::ALGO);
    }

    public static function decode(string $token): \stdClass
    {
        return JWT::decode($token, new Key(self::key(), self::ALGO));
    }

    /**
     * Key to encode|decode JWT-token.
     */
    private static function key(): string
    {
        return $_ENV['JWT_KEY'];
    }

    /**
     * Token lifetime in seconds.
     */
    private static function lifetime(): int
    {
        return intval($_ENV['JWT_LIFETIME']);
    }
}

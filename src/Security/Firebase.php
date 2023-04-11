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
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class Firebase
{
    public const ALGO = 'HS256';
    public const REFRESH = 1800;

    public static function token(?UserInterface $user): array
    {
        if (is_null($user)) {
            throw new UserNotFoundException('User is null...');
        }

        if ($user instanceof JsonUser) {
            $data = $user->toArray();
        } else {
            $data = [
                'username' => $user->getUserIdentifier(),
            ];
        }

        $payloadAccess = self::payload($data);
        $payloadRefresh = self::payload($data, self::REFRESH);

        return [
            'access_token' => self::encode($payloadAccess),
            'expires_in' => self::lifetime(),
            'refresh_expires_in' => self::REFRESH,
            'refresh_token' => self::encode($payloadRefresh),
        ];
    }

    public static function payload(array $data, int $refresh = 0): array
    {
        $time = time();

        $expired = $refresh
            ? $time + $refresh
            : $time + self::lifetime();

        $sub = $data['email'] ?? 'unknown@gmail.com';
        unset($data['password'],$data['token']);

        $result = [
            'iat' => $time,
            'exp' => $expired,
            'sub' => $sub,
            'user' => $data,
        ];

        if ($refresh) {
            unset($result['user']);
        }

        return $result;
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

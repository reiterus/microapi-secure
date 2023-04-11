<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use MicroApi\Contract\JsonUserInterface;
use MicroApi\Contract\JwtTokenInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;

class JwtToken implements JwtTokenInterface
{
    protected int $time;
    protected int $expired;
    protected int $refresh;
    protected string $sub;
    protected array $data;
    protected int $lifetime;
    protected string $tokenAccess = '';
    protected string $tokenRefresh = '';

    public function __construct(
    ) {
        $this->time = time();
        $this->lifetime = $this->getLifetime();
        $this->expired = $this->time + $this->lifetime;
        $this->refresh = $this->getRefresh();
    }

    /**
     * Encode payload to JWT-token.
     */
    public function encode(array $payload): string
    {
        return JWT::encode($payload, $this->getKey(), self::ALGO);
    }

    /**
     * Decode JWT-token.
     */
    public function decode(string $token): array
    {
        return (array) JWT::decode($token, new Key($this->getKey(), self::ALGO));
    }

    /**
     * Get full payload.
     */
    public function getPayload(array $data, int $refresh = 0): array
    {
        unset($data[JsonUserInterface::PASSWORD],$data[JsonUserInterface::TOKEN]);
        $this->sub = $data[JsonUserInterface::EMAIL] ?? self::UNKNOWN_EMAIL;
        $this->data = $data;
        $result = $this->getBodyPayload();

        if ($refresh) {
            unset($result[self::DATA]);
        }

        return $result;
    }

    /**
     * Get full response.
     */
    public function getResponse(?UserInterface $user): array
    {
        if (is_null($user)) {
            throw new UserNotFoundException('User is null...');
        }

        if ($user instanceof JsonUserInterface) {
            $data = $user->toArray();
        } else {
            $data = [
                JsonUserInterface::USERNAME => $user->getUserIdentifier(),
            ];
        }

        $payloadAccess = $this->getPayload($data);
        $payloadRefresh = $this->getPayload($data, $this->refresh);
        $this->tokenAccess = $this->encode($payloadAccess);
        $this->tokenRefresh = $this->encode($payloadRefresh);

        return $this->getBodyResponse();
    }

    /**
     * Payload body structure.
     */
    public function getBodyPayload(): array
    {
        return [
            self::IAT => $this->time,
            self::EXP => $this->expired,
            self::SUB => $this->sub,
            self::DATA => $this->data,
        ];
    }

    /**
     * Response body structure.
     */
    public function getBodyResponse(): array
    {
        return [
            self::ACCESS_TOKEN => $this->tokenAccess,
            self::EXPIRES_IN => $this->lifetime,
            self::REFRESH_EXPIRES_IN => $this->refresh,
            self::REFRESH_TOKEN => $this->tokenRefresh,
        ];
    }

    /**
     * Public key.
     */
    public function getKey(): string
    {
        return strval($_ENV['JWT_KEY']);
    }

    /**
     * Token lifetime.
     */
    public function getLifetime(): int
    {
        return intval($_ENV['JWT_LIFETIME']);
    }

    /**
     * Refresh token lifetime.
     */
    public function getRefresh(): int
    {
        return intval($_ENV['JWT_REFRESH']);
    }
}

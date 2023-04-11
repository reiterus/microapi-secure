<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Contract;

use Symfony\Component\Security\Core\User\UserInterface;

interface JwtTokenInterface
{
    public const ALGO = 'HS256';
    public const IAT = 'iat';
    public const EXP = 'exp';
    public const SUB = 'sub';
    public const DATA = 'data';
    public const ACCESS_TOKEN = 'access_token';
    public const REFRESH_TOKEN = 'refresh_token';
    public const EXPIRES_IN = 'expires_in';
    public const REFRESH_EXPIRES_IN = 'refresh_expires_in';
    public const UNKNOWN_EMAIL = 'unknown@gmail.com';

    /**
     * Encode payload to JWT-token.
     */
    public function encode(array $payload): string;

    /**
     * Decode JWT-token.
     */
    public function decode(string $token): array;

    /**
     * Get full payload.
     */
    public function getPayload(array $data, int $refresh = 0): array;

    /**
     * Get full response.
     */
    public function getResponse(?UserInterface $user): array;

    /**
     * Payload body structure.
     */
    public function getBodyPayload(): array;

    /**
     * Response body structure.
     */
    public function getBodyResponse(): array;

    /**
     * Public key.
     */
    public function getKey(): string;

    /**
     * Token lifetime.
     */
    public function getLifetime(): int;

    /**
     * Refresh token lifetime.
     */
    public function getRefresh(): int;
}

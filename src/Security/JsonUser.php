<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use MicroApi\Contract\JsonUserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JsonUser implements UserInterface, PasswordAuthenticatedUserInterface, JsonUserInterface
{
    public function __construct(
        private readonly array $data
    ) {
    }

    /**
     * Get identifier for json-user.
     */
    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * Get json-user username.
     */
    public function getUsername(): string
    {
        return $this->data[self::USERNAME];
    }

    /**
     * Get json-user email.
     */
    public function getEmail(): string
    {
        return $this->data[self::EMAIL];
    }

    /**
     * Get json-user token.
     */
    public function getToken(): string
    {
        return $this->data[self::TOKEN];
    }

    /**
     * Get json-user password.
     */
    public function getPassword(): string
    {
        return $this->data[self::PASSWORD];
    }

    /**
     * Get json-user roles.
     *
     * @return array|string[]
     */
    public function getRoles(): array
    {
        return $this->data[self::ROLES];
    }

    public function eraseCredentials(): void
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method string getUsername()
 * @method string getEmail()
 * @method string getToken()
 */
class JsonUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        private readonly array $data
    ) {
    }

    public function __call(string $name, array $arguments): ?string
    {
        $key = str_replace('get', '', strtolower($name));

        return $this->data[$key];
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    public function getPassword(): ?string
    {
        return $this->data['password'];
    }

    public function getRoles(): array
    {
        return $this->data['roles'];
    }

    public function eraseCredentials(): void
    {
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function toJson(): string
    {
        return strval(json_encode($this->data, 128 | 256));
    }
}

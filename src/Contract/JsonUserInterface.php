<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Contract;

interface JsonUserInterface
{
    public const USERNAME = 'username';
    public const EMAIL = 'email';
    public const TOKEN = 'token';
    public const PASSWORD = 'password';
    public const ROLES = 'roles';

    /**
     * Get json-user username.
     */
    public function getUsername(): string;

    /**
     * Get json-user password.
     */
    public function getPassword(): string;

    /**
     * Get json-user email.
     */
    public function getEmail(): string;

    /**
     * Get json-user token.
     */
    public function getToken(): string;

    /**
     * Get json-user as array.
     */
    public function toArray(): array;
}

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
use MicroApi\Contract\JsonUserLoadInterface;

class JsonUserLoad implements JsonUserLoadInterface
{
    public function __construct(
        private readonly array $users
    ) {
    }

    /**
     * Load user from json-file by username and password.
     */
    public function byCredentials(string $username, string $password): ?array
    {
        $user = array_filter(
            $this->users,
            function (array $item) use ($username, $password) {
                return $username == $item[JsonUserInterface::USERNAME]
                    && $password == $item[JsonUserInterface::PASSWORD];
            }
        );

        return current($user);
    }

    /**
     * Load user from json-file by token.
     */
    public function byToken(string $value): ?array
    {
        return $this->load($value, JsonUserInterface::TOKEN);
    }

    /**
     * Load user from json-file by username.
     */
    public function byUsername(string $value): ?array
    {
        return $this->load($value, JsonUserInterface::USERNAME);
    }

    /**
     * Load user from json-file by email.
     */
    public function byEmail(string $value): ?array
    {
        return $this->load($value, JsonUserInterface::EMAIL);
    }

    /**
     * Load user by value from key.
     */
    private function load(string $value, string $key): ?array
    {
        $user = array_filter(
            $this->users,
            function (array $item) use ($value, $key) {
                return $value == $item[$key];
            }
        );

        return current($user) ?: null;
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

class JsonUserLoad
{
    public function __construct(
        private readonly array $users
    ) {
    }

    public function byCredentials(string $username, string $password): ?array
    {
        $user = array_filter(
            $this->users,
            function (array $item) use ($username, $password) {
                return $username == $item['username']
                    && $password == $item['password'];
            }
        );

        return current($user);
    }

    public function byToken(string $value): ?array
    {
        return $this->load($value, 'token');
    }

    public function byUsername(string $value): ?array
    {
        return $this->load($value, 'username');
    }

    public function byEmail(string $value): ?array
    {
        return $this->load($value, 'email');
    }

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

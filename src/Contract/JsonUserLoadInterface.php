<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Contract;

interface JsonUserLoadInterface
{
    /**
     * Load user from json-file by username and password.
     */
    public function byCredentials(string $username, string $password): ?array;

    /**
     * Load user from json-file by token.
     */
    public function byToken(string $value): ?array;

    /**
     * Load user from json-file by username.
     */
    public function byUsername(string $value): ?array;

    /**
     * Load user from json-file by email.
     */
    public function byEmail(string $value): ?array;
}

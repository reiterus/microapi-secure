<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class TokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        private readonly array $auth
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $identifier = $this->auth[$accessToken] ?? null;

        if (null === $identifier) {
            throw new BadCredentialsException('Invalid Access Token...');
        }

        return new UserBadge($identifier);
    }
}

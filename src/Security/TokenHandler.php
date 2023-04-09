<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use MicroApi\Util\MicroLog;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class TokenHandler implements AccessTokenHandlerInterface
{
    use MicroLog;

    public function __construct(
        private readonly JsonUserLoad $userLoad
    ) {
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $user = $this->userLoad->byToken($accessToken);
        $identifier = $user['username'] ?? null;

        if (null === $identifier) {
            $message = 'Invalid Access Token';
            $this->setLogPostfix('TOKEN_ACCESS');
            $this->log($message, ['token' => $accessToken]);

            throw new BadCredentialsException($message);
        }

        return new UserBadge($identifier);
    }
}

<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use Firebase\JWT\ExpiredException;
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
        $this->setLogPostfix('TOKEN_ACCESS');
    }

    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $user = $this->getJsonUser($accessToken);
        $identifier = $user['username'] ?? null;

        if (null === $identifier) {
            $message = 'Invalid Access Token';
            $this->log($message, ['token' => $accessToken]);

            throw new BadCredentialsException($message);
        }

        return new UserBadge($identifier);
    }

    private function getJsonUser(string $token): ?array
    {
        try {
            $decoded = Firebase::decode($token);
            $email = $decoded->sub;
            $user = $this->userLoad->byEmail($email);
        } catch (ExpiredException $exception) {
            // Expired JWT token
            throw $exception;
        } catch (\Exception $ex) {
            // Not a JWT token
            $user = $this->userLoad->byToken($token);
        }

        return $user;
    }
}

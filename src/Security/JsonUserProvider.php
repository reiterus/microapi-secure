<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Security;

use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JsonUserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly JsonUserLoad $userLoad
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $data = $this->userLoad->byUsername($identifier);

        if (null === $data) {
            throw new \UnexpectedValueException('User data is null...');
        }

        return new JsonUser($data);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof JsonUser) {
            $message = sprintf('Invalid user class "%s".', get_class($user));

            throw new UnsupportedUserException($message);
        }

        return $this->loadUserByIdentifier($user->getUsername());
    }

    public function supportsClass(string $class): bool
    {
        return JsonUser::class === $class || is_subclass_of($class, JsonUser::class);
    }
}

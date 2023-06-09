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
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JsonUserProvider implements UserProviderInterface
{
    public function __construct(
        private readonly JsonUserLoadInterface $userLoad
    ) {
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $data = $this->userLoad->byUsername($identifier);

        if (null === $data) {
            $message = sprintf('Bad username: %s', $identifier);

            throw new UserNotFoundException($message);
        }

        return new JsonUser($data);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof JsonUserInterface) {
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

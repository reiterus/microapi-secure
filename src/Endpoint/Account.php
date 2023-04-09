<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Endpoint;

use MicroApi\Security\Firebase;
use MicroApi\Security\JsonUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'api_account_')]
class Account extends AbstractController
{
    #[Route('admin', name: 'admin')]
    public function admin(): JsonResponse
    {
        return $this->json(['token' => $this->getJwtToken()]);
    }

    #[Route('manager', name: 'manager')]
    public function manager(): JsonResponse
    {
        return $this->json(['token' => $this->getJwtToken()]);
    }

    #[Route('user', name: 'user')]
    public function user(): JsonResponse
    {
        return $this->json(['token' => $this->getJwtToken()]);
    }

    #[Route('guest', name: 'guest')]
    public function guest(): JsonResponse
    {
        return $this->json(['token' => $this->getJwtToken()]);
    }

    private function getJwtToken(): string
    {
        $user = $this->getUser();

        if (is_null($user)) {
            throw new \UnexpectedValueException('User is null...');
        }

        if ($user instanceof JsonUser) {
            $data = $user->toArray();
        } else {
            $data = [
                'username' => $user->getUserIdentifier(),
            ];
        }

        $payload = Firebase::payload($data);

        return Firebase::encode($payload);
    }
}

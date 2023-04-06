<?php

declare(strict_types=1);

/*
 * This file is part of the MicroApi engine based on Symfony components.
 * (c) Pavel Vasin <reiterus@yandex.ru>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MicroApi\Endpoint;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'api_account_')]
class Account extends AbstractController
{
    #[Route('admin', name: 'admin')]
    public function admin(): JsonResponse
    {
        return $this->json($this->getDefaultData('Admin Account'));
    }

    #[Route('user', name: 'user')]
    public function user(): JsonResponse
    {
        return $this->json($this->getDefaultData('User Account'));
    }

    private function getDefaultData(string $page): array
    {
        return [
            'page' => $page,
            'identifier' => $this->getUser()?->getUserIdentifier(),
            'roles' => $this->getUser()?->getRoles(),
        ];
    }
}

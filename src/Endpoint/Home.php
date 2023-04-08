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
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;

class Home extends AbstractController
{
    #[Route('/', name: 'api_home_index')]
    public function index(): JsonResponse
    {
        $welcome = $this->getParameter('welcome');

        return $this->json(['home page', $welcome]);
    }

    #[Route('/in-memory', name: 'api_home_in_memory')]
    public function inMemory(): JsonResponse
    {
        $new = new InMemoryUser('new', 'micro.api.new', ['ROLE_NEW']);
        $admin = new InMemoryUserProvider();
        $admin = $admin->loadUserByIdentifier('admin');

        return $this->json([
            'new_roles' => $new->getRoles(),
            'admin_roles' => $admin->getRoles(),
        ]);
    }
}

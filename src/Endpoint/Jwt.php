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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/jwt', name: 'api_jwt_')]
class Jwt extends AbstractController
{
    #[Route('/decode', name: 'decode')]
    public function decode(Request $request): JsonResponse
    {
        $token = $request->headers->get('authorization');
        $token = trim(substr(strval($token), 6));
        $decoded = Firebase::decode($token);

        return $this->json($decoded);
    }
}

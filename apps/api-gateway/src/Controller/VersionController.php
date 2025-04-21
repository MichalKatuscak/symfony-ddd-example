<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class VersionController extends AbstractController
{
    #[Route('/api/version', name: 'api_version', methods: ['GET'])]
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'version' => '1.0.0',
            'supported_versions' => ['1.0', '1.1'],
        ]);
    }
}

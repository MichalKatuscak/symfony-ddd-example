<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    #[Route('/api/health', name: 'api_health_check', methods: ['GET'])]
    public function index(Connection $connection): JsonResponse
    {
        $status = 'ok';
        $checks = [];
        $statusCode = Response::HTTP_OK;

        // Check database connection
        try {
            $connection->executeQuery('SELECT 1');
            $checks['database'] = 'ok';
        } catch (\Exception $e) {
            $checks['database'] = 'error';
            $status = 'error';
            $statusCode = Response::HTTP_SERVICE_UNAVAILABLE;
        }

        return new JsonResponse([
            'status' => $status,
            'version' => '1.0.0',
            'timestamp' => (new \DateTimeImmutable())->format('c'),
            'checks' => $checks,
        ], $statusCode);
    }
}

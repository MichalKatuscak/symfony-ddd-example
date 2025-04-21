<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Server(
 *     url="/",
 *     description="Local development server"
 * )
 * 
 * @OA\Server(
 *     url="https://api.example.com",
 *     description="Production server"
 * )
 * 
 * @OA\Server(
 *     url="https://staging-api.example.com",
 *     description="Staging server"
 * )
 */
class Servers
{
}

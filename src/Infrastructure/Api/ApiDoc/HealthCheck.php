<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/health",
 *     summary="Health check",
 *     description="Check the health of the API",
 *     tags={"System"},
 *     @OA\Response(
 *         response=200,
 *         description="API is healthy",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="ok"),
 *             @OA\Property(property="version", type="string", example="1.0.0"),
 *             @OA\Property(property="timestamp", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=503,
 *         description="API is unhealthy",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="version", type="string", example="1.0.0"),
 *             @OA\Property(property="timestamp", type="string", format="date-time"),
 *             @OA\Property(
 *                 property="checks",
 *                 type="object",
 *                 @OA\Property(property="database", type="string", example="error"),
 *                 @OA\Property(property="redis", type="string", example="ok")
 *             )
 *         )
 *     )
 * )
 */
class HealthCheck
{
}

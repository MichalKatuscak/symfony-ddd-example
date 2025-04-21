<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 *     path="/api/version",
 *     summary="API version",
 *     description="Get the API version",
 *     tags={"System"},
 *     @OA\Response(
 *         response=200,
 *         description="API version",
 *         @OA\JsonContent(
 *             @OA\Property(property="version", type="string", example="1.0.0"),
 *             @OA\Property(
 *                 property="supported_versions",
 *                 type="array",
 *                 @OA\Items(type="string", example="1.0")
 *             )
 *         )
 *     )
 * )
 */
class ApiVersion
{
}

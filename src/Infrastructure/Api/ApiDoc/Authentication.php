<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
 * @OA\Response(
 *     response="Unauthorized",
 *     description="Unauthorized",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=401),
 *         @OA\Property(property="message", type="string", example="Unauthorized")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="Forbidden",
 *     description="Forbidden",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=403),
 *         @OA\Property(property="message", type="string", example="Forbidden")
 *     )
 * )
 */
class Authentication
{
}

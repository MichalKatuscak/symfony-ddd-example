<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="Forbidden",
 *     description="Forbidden",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=403),
 *         @OA\Property(property="message", type="string", example="Forbidden")
 *     )
 * )
 */
class Authorization
{
}

<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ValidationError",
 *     required={"status", "message", "errors"},
 *     @OA\Property(property="status", type="integer", example=400),
 *     @OA\Property(property="message", type="string", example="Validation failed"),
 *     @OA\Property(
 *         property="errors",
 *         type="object",
 *         example={"customerEmail": "This value should not be blank."}
 *     )
 * )
 */
class ValidationErrors
{
}

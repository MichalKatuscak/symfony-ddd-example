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
 */
class ApiSecurity
{
}

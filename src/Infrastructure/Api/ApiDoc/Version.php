<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *     parameter="apiVersion",
 *     name="X-API-Version",
 *     in="header",
 *     description="API version",
 *     required=false,
 *     @OA\Schema(
 *         type="string",
 *         default="1.0",
 *         enum={"1.0", "1.1"}
 *     )
 * )
 */
class Version
{
}

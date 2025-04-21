<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="CachedResponse",
 *     description="Cached response",
 *     @OA\Header(
 *         header="Cache-Control",
 *         description="Cache control directives",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="ETag",
 *         description="Entity tag",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="Last-Modified",
 *         description="Last modified date",
 *         @OA\Schema(type="string")
 *     )
 * )
 */
class Caching
{
}

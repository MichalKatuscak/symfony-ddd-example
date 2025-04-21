<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="OptionsResponse",
 *     description="CORS preflight response",
 *     @OA\Header(
 *         header="Access-Control-Allow-Origin",
 *         description="Allowed origins",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="Access-Control-Allow-Methods",
 *         description="Allowed methods",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="Access-Control-Allow-Headers",
 *         description="Allowed headers",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Header(
 *         header="Access-Control-Max-Age",
 *         description="Max age",
 *         @OA\Schema(type="integer")
 *     )
 * )
 */
class Cors
{
}

<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="NotAcceptable",
 *     description="Not acceptable",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=406),
 *         @OA\Property(property="message", type="string", example="Not acceptable")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="UnsupportedMediaType",
 *     description="Unsupported media type",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=415),
 *         @OA\Property(property="message", type="string", example="Unsupported media type")
 *     )
 * )
 */
class ContentNegotiation
{
}

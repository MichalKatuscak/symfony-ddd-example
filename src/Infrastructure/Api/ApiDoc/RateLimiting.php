<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="TooManyRequests",
 *     description="Too many requests",
 *     @OA\Header(
 *         header="X-RateLimit-Limit",
 *         description="The maximum number of requests allowed in a time period",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Header(
 *         header="X-RateLimit-Remaining",
 *         description="The number of requests left in the current time period",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Header(
 *         header="X-RateLimit-Reset",
 *         description="The time at which the rate limit resets, in UTC epoch seconds",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Header(
 *         header="Retry-After",
 *         description="The number of seconds to wait before retrying",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=429),
 *         @OA\Property(property="message", type="string", example="Too many requests")
 *     )
 * )
 */
class RateLimiting
{
}

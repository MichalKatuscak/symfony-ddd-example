<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Error",
 *     required={"status", "message"},
 *     @OA\Property(property="status", type="integer", example=400),
 *     @OA\Property(property="message", type="string", example="Invalid input")
 * )
 *
 * @OA\Response(
 *     response="BadRequest",
 *     description="Bad request",
 *     @OA\JsonContent(ref="#/components/schemas/Error")
 * )
 *
 * @OA\Response(
 *     response="NotFound",
 *     description="Resource not found",
 *     @OA\JsonContent(
 *         allOf={@OA\Schema(ref="#/components/schemas/Error")},
 *         @OA\Property(property="status", type="integer", example=404),
 *         @OA\Property(property="message", type="string", example="Resource not found")
 *     )
 * )
 *
 * @OA\Response(
 *     response="Unauthorized",
 *     description="Unauthorized",
 *     @OA\JsonContent(
 *         allOf={@OA\Schema(ref="#/components/schemas/Error")},
 *         @OA\Property(property="status", type="integer", example=401),
 *         @OA\Property(property="message", type="string", example="Unauthorized")
 *     )
 * )
 *
 * @OA\Response(
 *     response="Forbidden",
 *     description="Forbidden",
 *     @OA\JsonContent(
 *         allOf={@OA\Schema(ref="#/components/schemas/Error")},
 *         @OA\Property(property="status", type="integer", example=403),
 *         @OA\Property(property="message", type="string", example="Forbidden")
 *     )
 * )
 *
 * @OA\Response(
 *     response="TooManyRequests",
 *     description="Too many requests",
 *     @OA\JsonContent(
 *         allOf={@OA\Schema(ref="#/components/schemas/Error")},
 *         @OA\Property(property="status", type="integer", example=429),
 *         @OA\Property(property="message", type="string", example="Too many requests")
 *     )
 * )
 *
 * @OA\Response(
 *     response="InternalServerError",
 *     description="Internal server error",
 *     @OA\JsonContent(
 *         allOf={@OA\Schema(ref="#/components/schemas/Error")},
 *         @OA\Property(property="status", type="integer", example=500),
 *         @OA\Property(property="message", type="string", example="Internal server error")
 *     )
 * )
 */
class ErrorResponses
{
}

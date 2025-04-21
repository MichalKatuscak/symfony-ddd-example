<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Response(
 *     response="BadRequest",
 *     description="Bad request",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=400),
 *         @OA\Property(property="message", type="string", example="Bad request"),
 *         @OA\Property(
 *             property="errors",
 *             type="object",
 *             example={"customerEmail": "This value should not be blank."}
 *         )
 *     )
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
 * 
 * @OA\Response(
 *     response="NotFound",
 *     description="Resource not found",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=404),
 *         @OA\Property(property="message", type="string", example="Resource not found")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="MethodNotAllowed",
 *     description="Method not allowed",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=405),
 *         @OA\Property(property="message", type="string", example="Method not allowed")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="Conflict",
 *     description="Conflict",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=409),
 *         @OA\Property(property="message", type="string", example="Conflict")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="UnprocessableEntity",
 *     description="Unprocessable entity",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=422),
 *         @OA\Property(property="message", type="string", example="Unprocessable entity")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="TooManyRequests",
 *     description="Too many requests",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=429),
 *         @OA\Property(property="message", type="string", example="Too many requests")
 *     )
 * )
 * 
 * @OA\Response(
 *     response="InternalServerError",
 *     description="Internal server error",
 *     @OA\JsonContent(
 *         @OA\Property(property="status", type="integer", example=500),
 *         @OA\Property(property="message", type="string", example="Internal server error")
 *     )
 * )
 */
class CommonResponses
{
}

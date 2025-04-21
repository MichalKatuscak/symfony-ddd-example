<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Parameter(
 *     parameter="uuid",
 *     name="id",
 *     in="path",
 *     description="UUID identifier",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         format="uuid"
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="orderId",
 *     name="orderId",
 *     in="path",
 *     description="Order UUID identifier",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         format="uuid"
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="invoiceId",
 *     name="invoiceId",
 *     in="path",
 *     description="Invoice UUID identifier",
 *     required=true,
 *     @OA\Schema(
 *         type="string",
 *         format="uuid"
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="page",
 *     name="page",
 *     in="query",
 *     description="Page number",
 *     required=false,
 *     @OA\Schema(
 *         type="integer",
 *         default=1,
 *         minimum=1
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="limit",
 *     name="limit",
 *     in="query",
 *     description="Number of items per page",
 *     required=false,
 *     @OA\Schema(
 *         type="integer",
 *         default=10,
 *         minimum=1,
 *         maximum=100
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="sort",
 *     name="sort",
 *     in="query",
 *     description="Sort field and direction (e.g. createdAt:desc)",
 *     required=false,
 *     @OA\Schema(
 *         type="string"
 *     )
 * )
 * 
 * @OA\Parameter(
 *     parameter="filter",
 *     name="filter",
 *     in="query",
 *     description="Filter criteria (e.g. status:placed)",
 *     required=false,
 *     @OA\Schema(
 *         type="string"
 *     )
 * )
 * 
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
class ApiParameters
{
}

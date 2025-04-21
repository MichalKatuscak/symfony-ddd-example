<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Pagination",
 *     @OA\Property(property="page", type="integer", example=1),
 *     @OA\Property(property="limit", type="integer", example=10),
 *     @OA\Property(property="total", type="integer", example=100),
 *     @OA\Property(property="pages", type="integer", example=10),
 *     @OA\Property(
 *         property="_links",
 *         type="object",
 *         @OA\Property(
 *             property="self",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri", example="/api/orders?page=1&limit=10")
 *         ),
 *         @OA\Property(
 *             property="first",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri", example="/api/orders?page=1&limit=10")
 *         ),
 *         @OA\Property(
 *             property="last",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri", example="/api/orders?page=10&limit=10")
 *         ),
 *         @OA\Property(
 *             property="next",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri", example="/api/orders?page=2&limit=10")
 *         ),
 *         @OA\Property(
 *             property="prev",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri", example="/api/orders?page=1&limit=10")
 *         )
 *     )
 * )
 * 
 * @OA\Schema(
 *     schema="PaginatedOrders",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Pagination"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="items",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Order")
 *             )
 *         )
 *     }
 * )
 * 
 * @OA\Schema(
 *     schema="PaginatedInvoices",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Pagination"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="items",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Invoice")
 *             )
 *         )
 *     }
 * )
 * 
 * @OA\Schema(
 *     schema="PaginatedPayments",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Pagination"),
 *         @OA\Schema(
 *             @OA\Property(
 *                 property="items",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Payment")
 *             )
 *         )
 *     }
 * )
 */
class Pagination
{
}

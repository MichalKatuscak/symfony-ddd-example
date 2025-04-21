<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Link",
 *     @OA\Property(property="href", type="string", format="uri")
 * )
 * 
 * @OA\Schema(
 *     schema="OrderLinks",
 *     @OA\Property(property="self", ref="#/components/schemas/Link"),
 *     @OA\Property(property="invoice", ref="#/components/schemas/Link")
 * )
 * 
 * @OA\Schema(
 *     schema="InvoiceLinks",
 *     @OA\Property(property="self", ref="#/components/schemas/Link"),
 *     @OA\Property(property="order", ref="#/components/schemas/Link"),
 *     @OA\Property(property="payment", ref="#/components/schemas/Link")
 * )
 * 
 * @OA\Schema(
 *     schema="PaymentLinks",
 *     @OA\Property(property="self", ref="#/components/schemas/Link"),
 *     @OA\Property(property="invoice", ref="#/components/schemas/Link")
 * )
 */
class HalLinks
{
}

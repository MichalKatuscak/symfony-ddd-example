<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to orders"
 * )
 * 
 * @OA\Tag(
 *     name="Invoices",
 *     description="Operations related to invoices"
 * )
 * 
 * @OA\Tag(
 *     name="Payments",
 *     description="Operations related to payments"
 * )
 * 
 * @OA\Tag(
 *     name="System",
 *     description="System operations"
 * )
 */
class ApiTags
{
}

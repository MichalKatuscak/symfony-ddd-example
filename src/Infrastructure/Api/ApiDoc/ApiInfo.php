<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="DDD Monorepo API",
 *     version="1.0.0",
 *     description="API for Sales, Billing, and Payments domains",
 *     @OA\Contact(
 *         email="contact@example.com",
 *         name="API Support",
 *         url="https://example.com/support"
 *     ),
 *     @OA\License(
 *         name="MIT",
 *         url="https://opensource.org/licenses/MIT"
 *     )
 * )
 * 
 * @OA\Server(
 *     url="/",
 *     description="Local development server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * 
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
 */
class ApiInfo
{
}

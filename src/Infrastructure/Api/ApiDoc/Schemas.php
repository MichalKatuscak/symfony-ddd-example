<?php

declare(strict_types=1);

namespace Infrastructure\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Order",
 *     required={"id", "status", "customerEmail", "items", "total"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="status", type="string", enum={"draft", "placed", "cancelled", "completed"}),
 *     @OA\Property(property="customerEmail", type="string", format="email"),
 *     @OA\Property(property="customerName", type="string", nullable=true),
 *     @OA\Property(property="createdAt", type="string", format="date-time"),
 *     @OA\Property(property="placedAt", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/OrderItem")
 *     ),
 *     @OA\Property(property="total", type="number", format="float"),
 *     @OA\Property(
 *         property="_links",
 *         type="object",
 *         @OA\Property(
 *             property="self",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         ),
 *         @OA\Property(
 *             property="invoice",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="OrderItem",
 *     required={"id", "productName", "unitPrice", "quantity", "total"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="productName", type="string"),
 *     @OA\Property(property="unitPrice", type="number", format="float"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="total", type="number", format="float")
 * )
 *
 * @OA\Schema(
 *     schema="Invoice",
 *     required={"id", "orderId", "invoiceNumber", "status", "customerEmail", "items", "total"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="orderId", type="string", format="uuid"),
 *     @OA\Property(property="invoiceNumber", type="string"),
 *     @OA\Property(property="status", type="string", enum={"draft", "issued", "paid", "cancelled"}),
 *     @OA\Property(property="customerEmail", type="string", format="email"),
 *     @OA\Property(property="customerName", type="string", nullable=true),
 *     @OA\Property(property="createdAt", type="string", format="date-time"),
 *     @OA\Property(property="issuedAt", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="paidAt", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="items",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/InvoiceItem")
 *     ),
 *     @OA\Property(property="total", type="number", format="float"),
 *     @OA\Property(
 *         property="_links",
 *         type="object",
 *         @OA\Property(
 *             property="self",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         ),
 *         @OA\Property(
 *             property="order",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         ),
 *         @OA\Property(
 *             property="payment",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         )
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="InvoiceItem",
 *     required={"id", "description", "unitPrice", "quantity", "total"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="unitPrice", type="number", format="float"),
 *     @OA\Property(property="quantity", type="integer"),
 *     @OA\Property(property="total", type="number", format="float")
 * )
 *
 * @OA\Schema(
 *     schema="Payment",
 *     required={"id", "invoiceId", "transactionId", "amount", "status", "method", "createdAt"},
 *     @OA\Property(property="id", type="string", format="uuid"),
 *     @OA\Property(property="invoiceId", type="string", format="uuid"),
 *     @OA\Property(property="transactionId", type="string"),
 *     @OA\Property(property="amount", type="number", format="float"),
 *     @OA\Property(property="status", type="string", enum={"pending", "completed", "failed", "refunded"}),
 *     @OA\Property(property="method", type="string"),
 *     @OA\Property(property="createdAt", type="string", format="date-time"),
 *     @OA\Property(property="completedAt", type="string", format="date-time", nullable=true),
 *     @OA\Property(
 *         property="_links",
 *         type="object",
 *         @OA\Property(
 *             property="self",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         ),
 *         @OA\Property(
 *             property="invoice",
 *             type="object",
 *             @OA\Property(property="href", type="string", format="uri")
 *         )
 *     )
 * )
 */
class Schemas
{
}

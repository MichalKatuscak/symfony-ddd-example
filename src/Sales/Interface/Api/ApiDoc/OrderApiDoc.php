<?php

declare(strict_types=1);

namespace Sales\Interface\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="Operations related to orders"
 * )
 */
class OrderApiDoc
{
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Create a new order",
     *     tags={"Orders"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"customerEmail", "items"},
     *             @OA\Property(property="customerEmail", type="string", format="email", example="customer@example.com"),
     *             @OA\Property(property="customerName", type="string", example="John Doe"),
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="productName", type="string", example="Laptop"),
     *                     @OA\Property(property="unitPrice", type="number", format="float", example=1299.99),
     *                     @OA\Property(property="quantity", type="integer", example=1)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function createOrder()
    {
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get an order by ID",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function getOrder()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/orders/{id}/place",
     *     summary="Place an order",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Order placed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid order state"
     *     )
     * )
     */
    public function placeOrder()
    {
    }
}

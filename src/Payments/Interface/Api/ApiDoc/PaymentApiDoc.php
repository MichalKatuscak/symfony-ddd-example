<?php

declare(strict_types=1);

namespace Payments\Interface\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Payments",
 *     description="Operations related to payments"
 * )
 */
class PaymentApiDoc
{
    /**
     * @OA\Get(
     *     path="/api/payments/{id}",
     *     summary="Get a payment by ID",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Payment details",
     *         @OA\JsonContent(ref="#/components/schemas/Payment")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     )
     * )
     */
    public function getPayment()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/payments/{id}/complete",
     *     summary="Complete a payment",
     *     tags={"Payments"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Payment completed successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Payment not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid payment state"
     *     )
     * )
     */
    public function completePayment()
    {
    }
}

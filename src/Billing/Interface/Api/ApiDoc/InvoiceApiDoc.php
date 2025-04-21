<?php

declare(strict_types=1);

namespace Billing\Interface\Api\ApiDoc;

use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Invoices",
 *     description="Operations related to invoices"
 * )
 */
class InvoiceApiDoc
{
    /**
     * @OA\Get(
     *     path="/api/invoices/{id}",
     *     summary="Get an invoice by ID",
     *     tags={"Invoices"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice details",
     *         @OA\JsonContent(ref="#/components/schemas/Invoice")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     )
     * )
     */
    public function getInvoice()
    {
    }

    /**
     * @OA\Post(
     *     path="/api/invoices/{id}/issue",
     *     summary="Issue an invoice",
     *     tags={"Invoices"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Invoice issued successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid invoice state"
     *     )
     * )
     */
    public function issueInvoice()
    {
    }
}

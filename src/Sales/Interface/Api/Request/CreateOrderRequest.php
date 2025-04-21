<?php

declare(strict_types=1);

namespace Sales\Interface\Api\Request;

use Symfony\Component\Validator\Constraints as Assert;

class CreateOrderRequest
{
    /**
     * @Assert\NotBlank
     * @Assert\Email
     */
    public string $customerEmail;

    public ?string $customerName = null;

    /**
     * @Assert\NotBlank
     * @Assert\Count(min=1, minMessage="At least one item is required")
     * @Assert\All({
     *     @Assert\Collection(
     *         fields={
     *             "productName": {
     *                 @Assert\NotBlank,
     *                 @Assert\Length(min=1, max=255)
     *             },
     *             "unitPrice": {
     *                 @Assert\NotBlank,
     *                 @Assert\Type("numeric"),
     *                 @Assert\GreaterThan(0)
     *             },
     *             "quantity": {
     *                 @Assert\NotBlank,
     *                 @Assert\Type("integer"),
     *                 @Assert\GreaterThan(0)
     *             }
     *         }
     *     )
     * })
     */
    public array $items = [];

    public function toCommand(string $id): array
    {
        return [
            'id' => $id,
            'customerEmail' => $this->customerEmail,
            'customerName' => $this->customerName,
            'items' => $this->items,
        ];
    }
}

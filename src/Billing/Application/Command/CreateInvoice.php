<?php

declare(strict_types=1);

namespace Billing\Application\Command;

final readonly class CreateInvoice
{
    /**
     * @param array<array{description: string, unitPrice: float, quantity: int}> $items
     */
    public function __construct(
        private string $id,
        private string $orderId,
        private string $invoiceNumber,
        private string $customerEmail,
        private ?string $customerName,
        private array $items
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getOrderId(): string
    {
        return $this->orderId;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    /**
     * @return array<array{description: string, unitPrice: float, quantity: int}>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

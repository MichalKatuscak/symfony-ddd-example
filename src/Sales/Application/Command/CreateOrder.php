<?php

declare(strict_types=1);

namespace Sales\Application\Command;

final readonly class CreateOrder
{
    /**
     * @param array<array{productName: string, unitPrice: float, quantity: int}> $items
     */
    public function __construct(
        private string $id,
        private string $customerEmail,
        private ?string $customerName,
        private array $items
    ) {
    }

    public function getId(): string
    {
        return $this->id;
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
     * @return array<array{productName: string, unitPrice: float, quantity: int}>
     */
    public function getItems(): array
    {
        return $this->items;
    }
}

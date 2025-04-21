<?php

declare(strict_types=1);

namespace Sales\Application\ReadModel;

use Sales\Domain\Model\OrderItem;

final readonly class OrderItemReadModel
{
    private function __construct(
        private string $id,
        private string $productName,
        private float $unitPrice,
        private int $quantity,
        private float $total
    ) {
    }

    public static function fromEntity(OrderItem $item): self
    {
        return new self(
            $item->getId()->getValue(),
            $item->getProductName(),
            $item->getUnitPrice()->getAmountAsFloat(),
            $item->getQuantity(),
            $item->getTotal()->getAmountAsFloat()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getUnitPrice(): float
    {
        return $this->unitPrice;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}

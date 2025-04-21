<?php

declare(strict_types=1);

namespace Sales\Domain\Model;

use InvalidArgumentException;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class OrderItem
{
    private UUID $id;
    private UUID $orderId;
    private string $productName;
    private Money $unitPrice;
    private int $quantity;

    public function __construct(
        UUID $id,
        UUID $orderId,
        string $productName,
        Money $unitPrice,
        int $quantity
    ) {
        if ($productName === '') {
            throw new InvalidArgumentException('Product name cannot be empty');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }

        $this->id = $id;
        $this->orderId = $orderId;
        $this->productName = $productName;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getOrderId(): UUID
    {
        return $this->orderId;
    }

    public function getProductName(): string
    {
        return $this->productName;
    }

    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): Money
    {
        return $this->unitPrice->multiply($this->quantity);
    }
}

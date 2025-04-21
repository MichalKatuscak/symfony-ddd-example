<?php

declare(strict_types=1);

namespace Billing\Domain\Model;

use InvalidArgumentException;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class InvoiceItem
{
    private UUID $id;
    private UUID $invoiceId;
    private string $description;
    private Money $unitPrice;
    private int $quantity;

    public function __construct(
        UUID $id,
        UUID $invoiceId,
        string $description,
        Money $unitPrice,
        int $quantity
    ) {
        if ($description === '') {
            throw new InvalidArgumentException('Description cannot be empty');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }

        $this->id = $id;
        $this->invoiceId = $invoiceId;
        $this->description = $description;
        $this->unitPrice = $unitPrice;
        $this->quantity = $quantity;
    }

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getInvoiceId(): UUID
    {
        return $this->invoiceId;
    }

    public function getDescription(): string
    {
        return $this->description;
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

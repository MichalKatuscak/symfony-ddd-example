<?php

declare(strict_types=1);

namespace Billing\Application\ReadModel;

use Billing\Domain\Model\InvoiceItem;

final readonly class InvoiceItemReadModel
{
    private function __construct(
        private string $id,
        private string $description,
        private float $unitPrice,
        private int $quantity,
        private float $total
    ) {
    }

    public static function fromEntity(InvoiceItem $item): self
    {
        return new self(
            $item->getId()->getValue(),
            $item->getDescription(),
            $item->getUnitPrice()->getAmountAsFloat(),
            $item->getQuantity(),
            $item->getTotal()->getAmountAsFloat()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
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

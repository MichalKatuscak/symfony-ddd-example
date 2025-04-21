<?php

declare(strict_types=1);

namespace Billing\Application\ReadModel;

use Billing\Domain\Model\Invoice;
use DateTimeImmutable;

final readonly class InvoiceReadModel
{
    /**
     * @param InvoiceItemReadModel[] $items
     */
    private function __construct(
        private string $id,
        private string $orderId,
        private string $invoiceNumber,
        private string $status,
        private string $customerEmail,
        private ?string $customerName,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $issuedAt,
        private ?DateTimeImmutable $paidAt,
        private array $items,
        private float $total
    ) {
    }

    public static function fromEntity(Invoice $invoice): self
    {
        $items = [];
        foreach ($invoice->getItems() as $item) {
            $items[] = InvoiceItemReadModel::fromEntity($item);
        }

        return new self(
            $invoice->getId()->getValue(),
            $invoice->getOrderId()->getValue(),
            $invoice->getInvoiceNumber(),
            $invoice->getStatus(),
            $invoice->getCustomerEmail()->getValue(),
            $invoice->getCustomerName(),
            $invoice->getCreatedAt(),
            $invoice->getIssuedAt(),
            $invoice->getPaidAt(),
            $items,
            $invoice->getTotal()->getAmountAsFloat()
        );
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

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getIssuedAt(): ?DateTimeImmutable
    {
        return $this->issuedAt;
    }

    public function getPaidAt(): ?DateTimeImmutable
    {
        return $this->paidAt;
    }

    /**
     * @return InvoiceItemReadModel[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): float
    {
        return $this->total;
    }
}

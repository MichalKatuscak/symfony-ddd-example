<?php

declare(strict_types=1);

namespace Billing\Domain\Model;

use Billing\Domain\Event\InvoiceIssued;
use DateTimeImmutable;
use InvalidArgumentException;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class Invoice
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_ISSUED = 'issued';
    public const STATUS_PAID = 'paid';
    public const STATUS_CANCELLED = 'cancelled';

    private UUID $id;
    private UUID $orderId;
    private string $invoiceNumber;
    private string $status;
    private Email $customerEmail;
    private ?string $customerName;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $issuedAt = null;
    private ?DateTimeImmutable $paidAt = null;
    /** @var InvoiceItem[] */
    private array $items = [];
    /** @var object[] */
    private array $events = [];

    private function __construct(
        UUID $id,
        UUID $orderId,
        string $invoiceNumber,
        Email $customerEmail,
        ?string $customerName
    ) {
        $this->id = $id;
        $this->orderId = $orderId;
        $this->invoiceNumber = $invoiceNumber;
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
        $this->status = self::STATUS_DRAFT;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
        UUID $id,
        UUID $orderId,
        string $invoiceNumber,
        Email $customerEmail,
        ?string $customerName = null
    ): self {
        return new self($id, $orderId, $invoiceNumber, $customerEmail, $customerName);
    }

    public function addItem(
        UUID $itemId,
        string $description,
        Money $unitPrice,
        int $quantity
    ): void {
        if ($this->status !== self::STATUS_DRAFT) {
            throw new InvalidArgumentException('Cannot add items to a non-draft invoice');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }

        $item = new InvoiceItem(
            $itemId,
            $this->id,
            $description,
            $unitPrice,
            $quantity
        );

        $this->items[] = $item;
    }

    public function issue(): void
    {
        if ($this->status !== self::STATUS_DRAFT) {
            throw new InvalidArgumentException('Only draft invoices can be issued');
        }

        if (empty($this->items)) {
            throw new InvalidArgumentException('Cannot issue an invoice with no items');
        }

        $this->status = self::STATUS_ISSUED;
        $this->issuedAt = new DateTimeImmutable();

        $this->recordEvent(new InvoiceIssued(
            $this->id,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->getTotal(),
            $this->issuedAt
        ));
    }

    public function markAsPaid(DateTimeImmutable $paidAt): void
    {
        if ($this->status !== self::STATUS_ISSUED) {
            throw new InvalidArgumentException('Only issued invoices can be marked as paid');
        }

        $this->status = self::STATUS_PAID;
        $this->paidAt = $paidAt;
    }

    public function cancel(): void
    {
        if ($this->status !== self::STATUS_ISSUED) {
            throw new InvalidArgumentException('Only issued invoices can be cancelled');
        }

        $this->status = self::STATUS_CANCELLED;
    }

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getOrderId(): UUID
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

    public function getCustomerEmail(): Email
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
     * @return InvoiceItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function getTotal(): Money
    {
        if (empty($this->items)) {
            return Money::fromCents(0, 'EUR');
        }

        $total = null;

        foreach ($this->items as $item) {
            $itemTotal = $item->getTotal();
            
            if ($total === null) {
                $total = $itemTotal;
            } else {
                $total = $total->add($itemTotal);
            }
        }

        return $total;
    }

    private function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * @return object[]
     */
    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        
        return $events;
    }
}

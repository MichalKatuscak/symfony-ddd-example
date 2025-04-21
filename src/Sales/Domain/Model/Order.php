<?php

declare(strict_types=1);

namespace Sales\Domain\Model;

use DateTimeImmutable;
use InvalidArgumentException;
use Sales\Domain\Event\OrderPlaced;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class Order
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PLACED = 'placed';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_COMPLETED = 'completed';

    private UUID $id;
    private string $status;
    private Email $customerEmail;
    private ?string $customerName;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $placedAt = null;
    /** @var OrderItem[] */
    private array $items = [];
    /** @var object[] */
    private array $events = [];

    private function __construct(
        UUID $id,
        Email $customerEmail,
        ?string $customerName
    ) {
        $this->id = $id;
        $this->customerEmail = $customerEmail;
        $this->customerName = $customerName;
        $this->status = self::STATUS_DRAFT;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
        UUID $id,
        Email $customerEmail,
        ?string $customerName = null
    ): self {
        return new self($id, $customerEmail, $customerName);
    }

    public function addItem(
        UUID $itemId,
        string $productName,
        Money $unitPrice,
        int $quantity
    ): void {
        if ($this->status !== self::STATUS_DRAFT) {
            throw new InvalidArgumentException('Cannot add items to a non-draft order');
        }

        if ($quantity <= 0) {
            throw new InvalidArgumentException('Quantity must be greater than zero');
        }

        $item = new OrderItem(
            $itemId,
            $this->id,
            $productName,
            $unitPrice,
            $quantity
        );

        $this->items[] = $item;
    }

    public function place(): void
    {
        if ($this->status !== self::STATUS_DRAFT) {
            throw new InvalidArgumentException('Only draft orders can be placed');
        }

        if (empty($this->items)) {
            throw new InvalidArgumentException('Cannot place an order with no items');
        }

        $this->status = self::STATUS_PLACED;
        $this->placedAt = new DateTimeImmutable();

        $this->recordEvent(new OrderPlaced(
            $this->id,
            $this->customerEmail,
            $this->getTotal(),
            $this->placedAt
        ));
    }

    public function cancel(): void
    {
        if ($this->status !== self::STATUS_PLACED) {
            throw new InvalidArgumentException('Only placed orders can be cancelled');
        }

        $this->status = self::STATUS_CANCELLED;
    }

    public function complete(): void
    {
        if ($this->status !== self::STATUS_PLACED) {
            throw new InvalidArgumentException('Only placed orders can be completed');
        }

        $this->status = self::STATUS_COMPLETED;
    }

    public function getId(): UUID
    {
        return $this->id;
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

    public function getPlacedAt(): ?DateTimeImmutable
    {
        return $this->placedAt;
    }

    /**
     * @return OrderItem[]
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

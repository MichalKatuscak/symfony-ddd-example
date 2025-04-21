<?php

declare(strict_types=1);

namespace Sales\Application\ReadModel;

use DateTimeImmutable;
use Sales\Domain\Model\Order;

final readonly class OrderReadModel
{
    /**
     * @param OrderItemReadModel[] $items
     */
    private function __construct(
        private string $id,
        private string $status,
        private string $customerEmail,
        private ?string $customerName,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $placedAt,
        private array $items,
        private float $total
    ) {
    }

    public static function fromEntity(Order $order): self
    {
        $items = [];
        foreach ($order->getItems() as $item) {
            $items[] = OrderItemReadModel::fromEntity($item);
        }

        return new self(
            $order->getId()->getValue(),
            $order->getStatus(),
            $order->getCustomerEmail()->getValue(),
            $order->getCustomerName(),
            $order->getCreatedAt(),
            $order->getPlacedAt(),
            $items,
            $order->getTotal()->getAmountAsFloat()
        );
    }

    public function getId(): string
    {
        return $this->id;
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

    public function getPlacedAt(): ?DateTimeImmutable
    {
        return $this->placedAt;
    }

    /**
     * @return OrderItemReadModel[]
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

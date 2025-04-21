<?php

declare(strict_types=1);

namespace Sales\Domain\Event;

use DateTimeImmutable;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

final readonly class OrderPlaced
{
    public function __construct(
        private UUID $orderId,
        private Email $customerEmail,
        private Money $total,
        private DateTimeImmutable $placedAt
    ) {
    }

    public function getOrderId(): UUID
    {
        return $this->orderId;
    }

    public function getCustomerEmail(): Email
    {
        return $this->customerEmail;
    }

    public function getTotal(): Money
    {
        return $this->total;
    }

    public function getPlacedAt(): DateTimeImmutable
    {
        return $this->placedAt;
    }
}

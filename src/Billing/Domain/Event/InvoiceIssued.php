<?php

declare(strict_types=1);

namespace Billing\Domain\Event;

use DateTimeImmutable;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

final readonly class InvoiceIssued
{
    public function __construct(
        private UUID $invoiceId,
        private UUID $orderId,
        private string $invoiceNumber,
        private Email $customerEmail,
        private Money $total,
        private DateTimeImmutable $issuedAt
    ) {
    }

    public function getInvoiceId(): UUID
    {
        return $this->invoiceId;
    }

    public function getOrderId(): UUID
    {
        return $this->orderId;
    }

    public function getInvoiceNumber(): string
    {
        return $this->invoiceNumber;
    }

    public function getCustomerEmail(): Email
    {
        return $this->customerEmail;
    }

    public function getTotal(): Money
    {
        return $this->total;
    }

    public function getIssuedAt(): DateTimeImmutable
    {
        return $this->issuedAt;
    }
}

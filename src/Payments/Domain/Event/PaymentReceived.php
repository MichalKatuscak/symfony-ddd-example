<?php

declare(strict_types=1);

namespace Payments\Domain\Event;

use DateTimeImmutable;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

final readonly class PaymentReceived
{
    public function __construct(
        private UUID $paymentId,
        private UUID $invoiceId,
        private string $transactionId,
        private Money $amount,
        private DateTimeImmutable $completedAt
    ) {
    }

    public function getPaymentId(): UUID
    {
        return $this->paymentId;
    }

    public function getInvoiceId(): UUID
    {
        return $this->invoiceId;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getAmount(): Money
    {
        return $this->amount;
    }

    public function getCompletedAt(): DateTimeImmutable
    {
        return $this->completedAt;
    }
}

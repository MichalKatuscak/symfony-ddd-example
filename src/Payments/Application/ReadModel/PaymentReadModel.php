<?php

declare(strict_types=1);

namespace Payments\Application\ReadModel;

use DateTimeImmutable;
use Payments\Domain\Model\Payment;

final readonly class PaymentReadModel
{
    private function __construct(
        private string $id,
        private string $invoiceId,
        private string $transactionId,
        private float $amount,
        private string $status,
        private string $method,
        private DateTimeImmutable $createdAt,
        private ?DateTimeImmutable $completedAt
    ) {
    }

    public static function fromEntity(Payment $payment): self
    {
        return new self(
            $payment->getId()->getValue(),
            $payment->getInvoiceId()->getValue(),
            $payment->getTransactionId(),
            $payment->getAmount()->getAmountAsFloat(),
            $payment->getStatus(),
            $payment->getMethod(),
            $payment->getCreatedAt(),
            $payment->getCompletedAt()
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getInvoiceId(): string
    {
        return $this->invoiceId;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCompletedAt(): ?DateTimeImmutable
    {
        return $this->completedAt;
    }
}

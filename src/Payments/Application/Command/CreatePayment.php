<?php

declare(strict_types=1);

namespace Payments\Application\Command;

final readonly class CreatePayment
{
    public function __construct(
        private string $id,
        private string $invoiceId,
        private string $transactionId,
        private float $amount,
        private string $method
    ) {
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

    public function getMethod(): string
    {
        return $this->method;
    }
}

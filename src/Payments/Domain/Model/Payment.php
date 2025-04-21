<?php

declare(strict_types=1);

namespace Payments\Domain\Model;

use DateTimeImmutable;
use InvalidArgumentException;
use Payments\Domain\Event\PaymentReceived;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class Payment
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_REFUNDED = 'refunded';

    private UUID $id;
    private UUID $invoiceId;
    private string $transactionId;
    private Money $amount;
    private string $status;
    private string $method;
    private DateTimeImmutable $createdAt;
    private ?DateTimeImmutable $completedAt = null;
    /** @var object[] */
    private array $events = [];

    private function __construct(
        UUID $id,
        UUID $invoiceId,
        string $transactionId,
        Money $amount,
        string $method
    ) {
        if ($transactionId === '') {
            throw new InvalidArgumentException('Transaction ID cannot be empty');
        }

        $this->id = $id;
        $this->invoiceId = $invoiceId;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
        $this->method = $method;
        $this->status = self::STATUS_PENDING;
        $this->createdAt = new DateTimeImmutable();
    }

    public static function create(
        UUID $id,
        UUID $invoiceId,
        string $transactionId,
        Money $amount,
        string $method
    ): self {
        return new self($id, $invoiceId, $transactionId, $amount, $method);
    }

    public function markAsCompleted(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new InvalidArgumentException('Only pending payments can be completed');
        }

        $this->status = self::STATUS_COMPLETED;
        $this->completedAt = new DateTimeImmutable();

        $this->recordEvent(new PaymentReceived(
            $this->id,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->completedAt
        ));
    }

    public function markAsFailed(): void
    {
        if ($this->status !== self::STATUS_PENDING) {
            throw new InvalidArgumentException('Only pending payments can be marked as failed');
        }

        $this->status = self::STATUS_FAILED;
    }

    public function refund(): void
    {
        if ($this->status !== self::STATUS_COMPLETED) {
            throw new InvalidArgumentException('Only completed payments can be refunded');
        }

        $this->status = self::STATUS_REFUNDED;
    }

    public function getId(): UUID
    {
        return $this->id;
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

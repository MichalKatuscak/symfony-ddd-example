<?php

declare(strict_types=1);

namespace Payments\Tests\Domain\Model;

use InvalidArgumentException;
use Payments\Domain\Event\PaymentReceived;
use Payments\Domain\Model\Payment;
use PHPUnit\Framework\TestCase;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class PaymentTest extends TestCase
{
    private UUID $paymentId;
    private UUID $invoiceId;
    private string $transactionId;
    private Money $amount;
    private string $method;

    protected function setUp(): void
    {
        $this->paymentId = UUID::generate();
        $this->invoiceId = UUID::generate();
        $this->transactionId = 'TRX-' . UUID::generate()->getValue();
        $this->amount = Money::fromFloat(99.99, 'EUR');
        $this->method = 'bank_transfer';
    }

    public function testCreatePayment(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $this->assertSame($this->paymentId, $payment->getId());
        $this->assertSame($this->invoiceId, $payment->getInvoiceId());
        $this->assertSame($this->transactionId, $payment->getTransactionId());
        $this->assertTrue($this->amount->equals($payment->getAmount()));
        $this->assertSame($this->method, $payment->getMethod());
        $this->assertSame(Payment::STATUS_PENDING, $payment->getStatus());
        $this->assertNull($payment->getCompletedAt());
    }

    public function testCannotCreatePaymentWithEmptyTransactionId(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transaction ID cannot be empty');

        Payment::create(
            $this->paymentId,
            $this->invoiceId,
            '',
            $this->amount,
            $this->method
        );
    }

    public function testMarkPaymentAsCompleted(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $payment->markAsCompleted();

        $this->assertSame(Payment::STATUS_COMPLETED, $payment->getStatus());
        $this->assertNotNull($payment->getCompletedAt());

        $events = $payment->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(PaymentReceived::class, $events[0]);
        $this->assertSame($this->paymentId, $events[0]->getPaymentId());
        $this->assertSame($this->invoiceId, $events[0]->getInvoiceId());
        $this->assertSame($this->transactionId, $events[0]->getTransactionId());
        $this->assertTrue($this->amount->equals($events[0]->getAmount()));
    }

    public function testCannotMarkNonPendingPaymentAsCompleted(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $payment->markAsCompleted();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only pending payments can be completed');

        $payment->markAsCompleted();
    }

    public function testMarkPaymentAsFailed(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $payment->markAsFailed();

        $this->assertSame(Payment::STATUS_FAILED, $payment->getStatus());
    }

    public function testCannotMarkNonPendingPaymentAsFailed(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $payment->markAsCompleted();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only pending payments can be marked as failed');

        $payment->markAsFailed();
    }

    public function testRefundPayment(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $payment->markAsCompleted();
        $payment->refund();

        $this->assertSame(Payment::STATUS_REFUNDED, $payment->getStatus());
    }

    public function testCannotRefundNonCompletedPayment(): void
    {
        $payment = Payment::create(
            $this->paymentId,
            $this->invoiceId,
            $this->transactionId,
            $this->amount,
            $this->method
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only completed payments can be refunded');

        $payment->refund();
    }
}

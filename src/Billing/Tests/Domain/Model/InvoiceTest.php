<?php

declare(strict_types=1);

namespace Billing\Tests\Domain\Model;

use Billing\Domain\Event\InvoiceIssued;
use Billing\Domain\Model\Invoice;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class InvoiceTest extends TestCase
{
    private UUID $invoiceId;
    private UUID $orderId;
    private string $invoiceNumber;
    private Email $customerEmail;
    private string $customerName;

    protected function setUp(): void
    {
        $this->invoiceId = UUID::generate();
        $this->orderId = UUID::generate();
        $this->invoiceNumber = 'INV-2023-00001';
        $this->customerEmail = Email::fromString('customer@example.com');
        $this->customerName = 'John Doe';
    }

    public function testCreateInvoice(): void
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $this->assertSame($this->invoiceId, $invoice->getId());
        $this->assertSame($this->orderId, $invoice->getOrderId());
        $this->assertSame($this->invoiceNumber, $invoice->getInvoiceNumber());
        $this->assertSame($this->customerEmail, $invoice->getCustomerEmail());
        $this->assertSame($this->customerName, $invoice->getCustomerName());
        $this->assertSame(Invoice::STATUS_DRAFT, $invoice->getStatus());
        $this->assertEmpty($invoice->getItems());
    }

    public function testAddItem(): void
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $itemId = UUID::generate();
        $description = 'Test Product';
        $unitPrice = Money::fromFloat(10.99, 'EUR');
        $quantity = 2;

        $invoice->addItem($itemId, $description, $unitPrice, $quantity);

        $items = $invoice->getItems();
        $this->assertCount(1, $items);
        $this->assertSame($itemId, $items[0]->getId());
        $this->assertSame($description, $items[0]->getDescription());
        $this->assertTrue($unitPrice->equals($items[0]->getUnitPrice()));
        $this->assertSame($quantity, $items[0]->getQuantity());
    }

    public function testCannotAddItemToIssuedInvoice(): void
    {
        $invoice = $this->createInvoiceWithItem();
        $invoice->issue();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot add items to a non-draft invoice');

        $invoice->addItem(
            UUID::generate(),
            'Another Product',
            Money::fromFloat(5.99, 'EUR'),
            1
        );
    }

    public function testCannotAddItemWithZeroQuantity(): void
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than zero');

        $invoice->addItem(
            UUID::generate(),
            'Test Product',
            Money::fromFloat(10.99, 'EUR'),
            0
        );
    }

    public function testIssueInvoice(): void
    {
        $invoice = $this->createInvoiceWithItem();
        $invoice->issue();

        $this->assertSame(Invoice::STATUS_ISSUED, $invoice->getStatus());
        $this->assertNotNull($invoice->getIssuedAt());

        $events = $invoice->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(InvoiceIssued::class, $events[0]);
        $this->assertSame($this->invoiceId, $events[0]->getInvoiceId());
        $this->assertSame($this->orderId, $events[0]->getOrderId());
        $this->assertSame($this->invoiceNumber, $events[0]->getInvoiceNumber());
    }

    public function testCannotIssueEmptyInvoice(): void
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot issue an invoice with no items');

        $invoice->issue();
    }

    public function testCannotIssueAlreadyIssuedInvoice(): void
    {
        $invoice = $this->createInvoiceWithItem();
        $invoice->issue();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only draft invoices can be issued');

        $invoice->issue();
    }

    public function testMarkInvoiceAsPaid(): void
    {
        $invoice = $this->createInvoiceWithItem();
        $invoice->issue();

        $paidAt = new \DateTimeImmutable();
        $invoice->markAsPaid($paidAt);

        $this->assertSame(Invoice::STATUS_PAID, $invoice->getStatus());
        $this->assertSame($paidAt, $invoice->getPaidAt());
    }

    public function testCannotMarkNonIssuedInvoiceAsPaid(): void
    {
        $invoice = $this->createInvoiceWithItem();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only issued invoices can be marked as paid');

        $invoice->markAsPaid(new \DateTimeImmutable());
    }

    public function testGetTotal(): void
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $invoice->addItem(
            UUID::generate(),
            'Product 1',
            Money::fromFloat(10.00, 'EUR'),
            2
        );

        $invoice->addItem(
            UUID::generate(),
            'Product 2',
            Money::fromFloat(5.00, 'EUR'),
            3
        );

        $expectedTotal = Money::fromFloat(35.00, 'EUR');
        $this->assertTrue($expectedTotal->equals($invoice->getTotal()));
    }

    private function createInvoiceWithItem(): Invoice
    {
        $invoice = Invoice::create(
            $this->invoiceId,
            $this->orderId,
            $this->invoiceNumber,
            $this->customerEmail,
            $this->customerName
        );

        $invoice->addItem(
            UUID::generate(),
            'Test Product',
            Money::fromFloat(10.99, 'EUR'),
            2
        );

        return $invoice;
    }
}

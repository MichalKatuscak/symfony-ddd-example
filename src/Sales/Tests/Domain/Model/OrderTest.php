<?php

declare(strict_types=1);

namespace Sales\Tests\Domain\Model;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Sales\Domain\Event\OrderPlaced;
use Sales\Domain\Model\Order;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;

class OrderTest extends TestCase
{
    private UUID $orderId;
    private Email $customerEmail;
    private string $customerName;

    protected function setUp(): void
    {
        $this->orderId = UUID::generate();
        $this->customerEmail = Email::fromString('customer@example.com');
        $this->customerName = 'John Doe';
    }

    public function testCreateOrder(): void
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $this->assertSame($this->orderId, $order->getId());
        $this->assertSame($this->customerEmail, $order->getCustomerEmail());
        $this->assertSame($this->customerName, $order->getCustomerName());
        $this->assertSame(Order::STATUS_DRAFT, $order->getStatus());
        $this->assertEmpty($order->getItems());
    }

    public function testAddItem(): void
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $itemId = UUID::generate();
        $productName = 'Test Product';
        $unitPrice = Money::fromFloat(10.99, 'EUR');
        $quantity = 2;

        $order->addItem($itemId, $productName, $unitPrice, $quantity);

        $items = $order->getItems();
        $this->assertCount(1, $items);
        $this->assertSame($itemId, $items[0]->getId());
        $this->assertSame($productName, $items[0]->getProductName());
        $this->assertTrue($unitPrice->equals($items[0]->getUnitPrice()));
        $this->assertSame($quantity, $items[0]->getQuantity());
    }

    public function testCannotAddItemToPlacedOrder(): void
    {
        $order = $this->createOrderWithItem();
        $order->place();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot add items to a non-draft order');

        $order->addItem(
            UUID::generate(),
            'Another Product',
            Money::fromFloat(5.99, 'EUR'),
            1
        );
    }

    public function testCannotAddItemWithZeroQuantity(): void
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Quantity must be greater than zero');

        $order->addItem(
            UUID::generate(),
            'Test Product',
            Money::fromFloat(10.99, 'EUR'),
            0
        );
    }

    public function testPlaceOrder(): void
    {
        $order = $this->createOrderWithItem();
        $order->place();

        $this->assertSame(Order::STATUS_PLACED, $order->getStatus());
        $this->assertNotNull($order->getPlacedAt());

        $events = $order->releaseEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(OrderPlaced::class, $events[0]);
        $this->assertSame($this->orderId, $events[0]->getOrderId());
    }

    public function testCannotPlaceEmptyOrder(): void
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Cannot place an order with no items');

        $order->place();
    }

    public function testCannotPlaceAlreadyPlacedOrder(): void
    {
        $order = $this->createOrderWithItem();
        $order->place();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Only draft orders can be placed');

        $order->place();
    }

    public function testGetTotal(): void
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $order->addItem(
            UUID::generate(),
            'Product 1',
            Money::fromFloat(10.00, 'EUR'),
            2
        );

        $order->addItem(
            UUID::generate(),
            'Product 2',
            Money::fromFloat(5.00, 'EUR'),
            3
        );

        $expectedTotal = Money::fromFloat(35.00, 'EUR');
        $this->assertTrue($expectedTotal->equals($order->getTotal()));
    }

    private function createOrderWithItem(): Order
    {
        $order = Order::create(
            $this->orderId,
            $this->customerEmail,
            $this->customerName
        );

        $order->addItem(
            UUID::generate(),
            'Test Product',
            Money::fromFloat(10.99, 'EUR'),
            2
        );

        return $order;
    }
}

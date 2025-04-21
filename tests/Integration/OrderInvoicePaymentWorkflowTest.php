<?php

declare(strict_types=1);

namespace Tests\Integration;

use Billing\Domain\Repository\InvoiceRepository;
use Payments\Domain\Repository\PaymentRepository;
use PHPUnit\Framework\TestCase;
use Sales\Application\Command\CreateOrder;
use Sales\Application\Command\PlaceOrder;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class OrderInvoicePaymentWorkflowTest extends KernelTestCase
{
    private MessageBusInterface $commandBus;
    private OrderRepository $orderRepository;
    private InvoiceRepository $invoiceRepository;
    private PaymentRepository $paymentRepository;

    protected static function createKernel(array $options = []): \Symfony\Component\HttpKernel\KernelInterface
    {
        return new \App\Kernel($_SERVER['APP_ENV'] ?? 'test', (bool) ($_SERVER['APP_DEBUG'] ?? true));
    }

    protected function setUp(): void
    {
        self::bootKernel();

        $this->commandBus = static::getContainer()->get('command.bus');
        $this->orderRepository = static::getContainer()->get(OrderRepository::class);
        $this->invoiceRepository = static::getContainer()->get(InvoiceRepository::class);
        $this->paymentRepository = static::getContainer()->get(PaymentRepository::class);
    }

    public function testOrderInvoicePaymentWorkflow(): void
    {
        // 1. Create an order
        $orderId = UUID::generate()->getValue();
        $createOrderCommand = new CreateOrder(
            $orderId,
            'customer@example.com',
            'John Doe',
            [
                [
                    'productName' => 'Test Product',
                    'unitPrice' => 99.99,
                    'quantity' => 1
                ]
            ]
        );
        $this->commandBus->dispatch($createOrderCommand);

        // 2. Place the order
        $placeOrderCommand = new PlaceOrder($orderId);
        $this->commandBus->dispatch($placeOrderCommand);

        // Wait for async events to be processed
        sleep(1);

        // 3. Verify order status
        $order = $this->orderRepository->findById(UUID::fromString($orderId));
        $this->assertNotNull($order);
        $this->assertEquals('placed', $order->getStatus());

        // 4. Verify invoice was created
        $invoice = $this->invoiceRepository->findByOrderId(UUID::fromString($orderId));
        $this->assertNotNull($invoice);
        $this->assertEquals('issued', $invoice->getStatus());

        // 5. Verify payment was created
        $payment = $this->paymentRepository->findByInvoiceId($invoice->getId());
        $this->assertNotNull($payment);
        $this->assertEquals('pending', $payment->getStatus());

        // 6. Complete the payment
        $payment->markAsCompleted();
        $this->paymentRepository->save($payment);

        // Wait for async events to be processed
        sleep(1);

        // 7. Verify invoice was marked as paid
        $invoice = $this->invoiceRepository->findById($invoice->getId());
        $this->assertEquals('paid', $invoice->getStatus());
    }
}

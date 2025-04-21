<?php

declare(strict_types=1);

namespace Billing\Application\EventHandler;

use Billing\Domain\Model\Invoice;
use Billing\Domain\Repository\InvoiceRepository;
use Billing\Domain\Service\InvoiceNumberGenerator;
use Sales\Domain\Event\OrderPlaced;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class OrderPlacedHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private InvoiceRepository $invoiceRepository,
        private InvoiceNumberGenerator $invoiceNumberGenerator,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(OrderPlaced $event): void
    {
        $orderId = $event->getOrderId();
        $order = $this->orderRepository->findById($orderId);

        if ($order === null) {
            return;
        }

        $invoiceId = UUID::generate();
        $invoiceNumber = $this->invoiceNumberGenerator->generate();

        $invoice = Invoice::create(
            $invoiceId,
            $orderId,
            $invoiceNumber,
            $order->getCustomerEmail(),
            $order->getCustomerName()
        );

        foreach ($order->getItems() as $item) {
            $invoice->addItem(
                UUID::generate(),
                $item->getProductName(),
                $item->getUnitPrice(),
                $item->getQuantity()
            );
        }

        $invoice->issue();
        $this->invoiceRepository->save($invoice);

        // Dispatch domain events
        foreach ($invoice->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}

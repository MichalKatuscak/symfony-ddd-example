<?php

declare(strict_types=1);

namespace Infrastructure\EventStore;

use Billing\Domain\Event\InvoiceIssued;
use Billing\Domain\Model\Invoice;
use Payments\Domain\Event\PaymentReceived;
use Payments\Domain\Model\Payment;
use Sales\Domain\Event\OrderPlaced;
use Sales\Domain\Model\Order;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

class EventStoreSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private EventStore $eventStore
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            WorkerMessageHandledEvent::class => 'onMessageHandled',
        ];
    }

    public function onMessageHandled(WorkerMessageHandledEvent $event): void
    {
        $message = $event->getEnvelope()->getMessage();

        if ($message instanceof OrderPlaced) {
            $this->eventStore->append($message, $message->getOrderId(), Order::class);
        } elseif ($message instanceof InvoiceIssued) {
            $this->eventStore->append($message, $message->getInvoiceId(), Invoice::class);
        } elseif ($message instanceof PaymentReceived) {
            $this->eventStore->append($message, $message->getPaymentId(), Payment::class);
        }
    }
}

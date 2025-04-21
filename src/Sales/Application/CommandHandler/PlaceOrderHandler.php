<?php

declare(strict_types=1);

namespace Sales\Application\CommandHandler;

use InvalidArgumentException;
use Sales\Application\Command\PlaceOrder;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class PlaceOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(PlaceOrder $command): void
    {
        $orderId = UUID::fromString($command->getId());
        $order = $this->orderRepository->findById($orderId);

        if ($order === null) {
            throw new InvalidArgumentException('Order not found');
        }

        $order->place();
        $this->orderRepository->save($order);

        // Dispatch domain events
        foreach ($order->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}

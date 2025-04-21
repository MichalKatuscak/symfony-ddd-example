<?php

declare(strict_types=1);

namespace Sales\Application\CommandHandler;

use Sales\Application\Command\CreateOrder;
use Sales\Domain\Model\Order;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {
    }

    public function __invoke(CreateOrder $command): void
    {
        $orderId = UUID::fromString($command->getId());
        $customerEmail = Email::fromString($command->getCustomerEmail());

        $order = Order::create(
            $orderId,
            $customerEmail,
            $command->getCustomerName()
        );

        foreach ($command->getItems() as $item) {
            $order->addItem(
                UUID::generate(),
                $item['productName'],
                Money::fromFloat($item['unitPrice'], 'EUR'),
                $item['quantity']
            );
        }

        $this->orderRepository->save($order);
    }
}

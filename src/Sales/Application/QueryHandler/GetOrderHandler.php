<?php

declare(strict_types=1);

namespace Sales\Application\QueryHandler;

use InvalidArgumentException;
use Sales\Application\Query\GetOrder;
use Sales\Application\ReadModel\OrderReadModel;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository
    ) {
    }

    public function __invoke(GetOrder $query): OrderReadModel
    {
        $orderId = UUID::fromString($query->getId());
        $order = $this->orderRepository->findById($orderId);

        if ($order === null) {
            throw new InvalidArgumentException('Order not found');
        }

        return OrderReadModel::fromEntity($order);
    }
}

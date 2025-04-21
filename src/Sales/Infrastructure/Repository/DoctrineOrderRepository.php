<?php

declare(strict_types=1);

namespace Sales\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Sales\Domain\Model\Order;
use Sales\Domain\Repository\OrderRepository;
use Sales\Domain\ValueObject\UUID;

class DoctrineOrderRepository implements OrderRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Order $order): void
    {
        $this->entityManager->persist($order);
        $this->entityManager->flush();
    }

    public function findById(UUID $id): ?Order
    {
        return $this->entityManager->find(Order::class, $id);
    }

    /**
     * @return Order[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Order::class)->findAll();
    }
}

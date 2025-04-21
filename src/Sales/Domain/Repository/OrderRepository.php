<?php

declare(strict_types=1);

namespace Sales\Domain\Repository;

use Sales\Domain\Model\Order;
use Sales\Domain\ValueObject\UUID;

interface OrderRepository
{
    public function save(Order $order): void;
    
    public function findById(UUID $id): ?Order;
    
    /**
     * @return Order[]
     */
    public function findAll(): array;
}

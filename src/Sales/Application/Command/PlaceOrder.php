<?php

declare(strict_types=1);

namespace Sales\Application\Command;

final readonly class PlaceOrder
{
    public function __construct(
        private string $id
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }
}

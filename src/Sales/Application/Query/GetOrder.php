<?php

declare(strict_types=1);

namespace Sales\Application\Query;

final readonly class GetOrder
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

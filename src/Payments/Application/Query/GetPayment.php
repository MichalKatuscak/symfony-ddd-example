<?php

declare(strict_types=1);

namespace Payments\Application\Query;

final readonly class GetPayment
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

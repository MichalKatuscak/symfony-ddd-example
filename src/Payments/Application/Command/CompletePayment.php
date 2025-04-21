<?php

declare(strict_types=1);

namespace Payments\Application\Command;

final readonly class CompletePayment
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

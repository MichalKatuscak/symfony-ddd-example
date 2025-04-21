<?php

declare(strict_types=1);

namespace Billing\Application\Query;

final readonly class GetInvoice
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

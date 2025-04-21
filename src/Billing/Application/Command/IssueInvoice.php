<?php

declare(strict_types=1);

namespace Billing\Application\Command;

final readonly class IssueInvoice
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

<?php

declare(strict_types=1);

namespace Payments\Infrastructure\Service;

use Payments\Domain\Service\TransactionIdGenerator;
use Ramsey\Uuid\Uuid;

class UuidTransactionIdGenerator implements TransactionIdGenerator
{
    private string $prefix;

    public function __construct(string $prefix = 'TRX')
    {
        $this->prefix = $prefix;
    }

    public function generate(): string
    {
        return $this->prefix . '-' . Uuid::uuid4()->toString();
    }
}

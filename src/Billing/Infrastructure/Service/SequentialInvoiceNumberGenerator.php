<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Service;

use Billing\Domain\Service\InvoiceNumberGenerator;

class SequentialInvoiceNumberGenerator implements InvoiceNumberGenerator
{
    private int $lastNumber;
    private string $prefix;
    private int $year;

    public function __construct(
        int $lastNumber = 0,
        ?string $prefix = null,
        ?int $year = null
    ) {
        $this->lastNumber = $lastNumber;
        $this->prefix = $prefix ?? 'INV';
        $this->year = $year ?? (int) date('Y');
    }

    public function generate(): string
    {
        $this->lastNumber++;
        
        return sprintf(
            '%s-%d-%06d',
            $this->prefix,
            $this->year,
            $this->lastNumber
        );
    }
}

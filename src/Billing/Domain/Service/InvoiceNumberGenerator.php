<?php

declare(strict_types=1);

namespace Billing\Domain\Service;

interface InvoiceNumberGenerator
{
    public function generate(): string;
}

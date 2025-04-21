<?php

declare(strict_types=1);

namespace Payments\Domain\Service;

interface TransactionIdGenerator
{
    public function generate(): string;
}

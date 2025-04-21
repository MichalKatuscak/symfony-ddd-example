<?php

declare(strict_types=1);

namespace Billing\Domain\Repository;

use Billing\Domain\Model\Invoice;
use Sales\Domain\ValueObject\UUID;

interface InvoiceRepository
{
    public function save(Invoice $invoice): void;
    
    public function findById(UUID $id): ?Invoice;
    
    public function findByOrderId(UUID $orderId): ?Invoice;
    
    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice;
    
    /**
     * @return Invoice[]
     */
    public function findAll(): array;
}

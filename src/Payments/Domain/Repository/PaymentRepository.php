<?php

declare(strict_types=1);

namespace Payments\Domain\Repository;

use Payments\Domain\Model\Payment;
use Sales\Domain\ValueObject\UUID;

interface PaymentRepository
{
    public function save(Payment $payment): void;
    
    public function findById(UUID $id): ?Payment;
    
    public function findByInvoiceId(UUID $invoiceId): ?Payment;
    
    public function findByTransactionId(string $transactionId): ?Payment;
    
    /**
     * @return Payment[]
     */
    public function findAll(): array;
}

<?php

declare(strict_types=1);

namespace Payments\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Payments\Domain\Model\Payment;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\UUID;

class DoctrinePaymentRepository implements PaymentRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Payment $payment): void
    {
        $this->entityManager->persist($payment);
        $this->entityManager->flush();
    }

    public function findById(UUID $id): ?Payment
    {
        return $this->entityManager->find(Payment::class, $id);
    }

    public function findByInvoiceId(UUID $invoiceId): ?Payment
    {
        return $this->entityManager->getRepository(Payment::class)
            ->findOneBy(['invoiceId' => $invoiceId]);
    }

    public function findByTransactionId(string $transactionId): ?Payment
    {
        return $this->entityManager->getRepository(Payment::class)
            ->findOneBy(['transactionId' => $transactionId]);
    }

    /**
     * @return Payment[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Payment::class)->findAll();
    }
}

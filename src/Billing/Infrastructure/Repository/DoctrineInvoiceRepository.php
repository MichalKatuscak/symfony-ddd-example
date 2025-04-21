<?php

declare(strict_types=1);

namespace Billing\Infrastructure\Repository;

use Billing\Domain\Model\Invoice;
use Billing\Domain\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sales\Domain\ValueObject\UUID;

class DoctrineInvoiceRepository implements InvoiceRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function save(Invoice $invoice): void
    {
        $this->entityManager->persist($invoice);
        $this->entityManager->flush();
    }

    public function findById(UUID $id): ?Invoice
    {
        return $this->entityManager->find(Invoice::class, $id);
    }

    public function findByOrderId(UUID $orderId): ?Invoice
    {
        return $this->entityManager->getRepository(Invoice::class)
            ->findOneBy(['orderId' => $orderId]);
    }

    public function findByInvoiceNumber(string $invoiceNumber): ?Invoice
    {
        return $this->entityManager->getRepository(Invoice::class)
            ->findOneBy(['invoiceNumber' => $invoiceNumber]);
    }

    /**
     * @return Invoice[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Invoice::class)->findAll();
    }
}

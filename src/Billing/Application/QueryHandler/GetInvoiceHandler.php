<?php

declare(strict_types=1);

namespace Billing\Application\QueryHandler;

use Billing\Application\Query\GetInvoice;
use Billing\Application\ReadModel\InvoiceReadModel;
use Billing\Domain\Repository\InvoiceRepository;
use InvalidArgumentException;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetInvoiceHandler
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }

    public function __invoke(GetInvoice $query): InvoiceReadModel
    {
        $invoiceId = UUID::fromString($query->getId());
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if ($invoice === null) {
            throw new InvalidArgumentException('Invoice not found');
        }

        return InvoiceReadModel::fromEntity($invoice);
    }
}

<?php

declare(strict_types=1);

namespace Billing\Application\CommandHandler;

use Billing\Application\Command\CreateInvoice;
use Billing\Domain\Model\Invoice;
use Billing\Domain\Repository\InvoiceRepository;
use Sales\Domain\ValueObject\Email;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreateInvoiceHandler
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }

    public function __invoke(CreateInvoice $command): void
    {
        $invoiceId = UUID::fromString($command->getId());
        $orderId = UUID::fromString($command->getOrderId());
        $customerEmail = Email::fromString($command->getCustomerEmail());

        $invoice = Invoice::create(
            $invoiceId,
            $orderId,
            $command->getInvoiceNumber(),
            $customerEmail,
            $command->getCustomerName()
        );

        foreach ($command->getItems() as $item) {
            $invoice->addItem(
                UUID::generate(),
                $item['description'],
                Money::fromFloat($item['unitPrice'], 'EUR'),
                $item['quantity']
            );
        }

        $this->invoiceRepository->save($invoice);
    }
}

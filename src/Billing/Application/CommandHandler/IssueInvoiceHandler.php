<?php

declare(strict_types=1);

namespace Billing\Application\CommandHandler;

use Billing\Application\Command\IssueInvoice;
use Billing\Domain\Repository\InvoiceRepository;
use InvalidArgumentException;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class IssueInvoiceHandler
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(IssueInvoice $command): void
    {
        $invoiceId = UUID::fromString($command->getId());
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if ($invoice === null) {
            throw new InvalidArgumentException('Invoice not found');
        }

        $invoice->issue();
        $this->invoiceRepository->save($invoice);

        // Dispatch domain events
        foreach ($invoice->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}

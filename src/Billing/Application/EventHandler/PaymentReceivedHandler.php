<?php

declare(strict_types=1);

namespace Billing\Application\EventHandler;

use Billing\Domain\Repository\InvoiceRepository;
use Payments\Domain\Event\PaymentReceived;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class PaymentReceivedHandler
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }

    public function __invoke(PaymentReceived $event): void
    {
        $invoiceId = $event->getInvoiceId();
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if ($invoice === null) {
            return;
        }

        $invoice->markAsPaid($event->getCompletedAt());
        $this->invoiceRepository->save($invoice);
    }
}

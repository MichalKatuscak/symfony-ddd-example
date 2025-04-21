<?php

declare(strict_types=1);

namespace Payments\Application\EventHandler;

use Billing\Domain\Event\InvoiceIssued;
use Billing\Domain\Repository\InvoiceRepository;
use Payments\Domain\Model\Payment;
use Payments\Domain\Repository\PaymentRepository;
use Payments\Domain\Service\TransactionIdGenerator;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class InvoiceIssuedHandler
{
    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private PaymentRepository $paymentRepository,
        private TransactionIdGenerator $transactionIdGenerator
    ) {
    }

    public function __invoke(InvoiceIssued $event): void
    {
        $invoiceId = $event->getInvoiceId();
        $invoice = $this->invoiceRepository->findById($invoiceId);

        if ($invoice === null) {
            return;
        }

        $paymentId = UUID::generate();
        $transactionId = $this->transactionIdGenerator->generate();

        $payment = Payment::create(
            $paymentId,
            $invoiceId,
            $transactionId,
            $invoice->getTotal(),
            'bank_transfer'
        );

        $this->paymentRepository->save($payment);
    }
}

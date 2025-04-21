<?php

declare(strict_types=1);

namespace Payments\Application\CommandHandler;

use Payments\Application\Command\CreatePayment;
use Payments\Domain\Model\Payment;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\Money;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class CreatePaymentHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {
    }

    public function __invoke(CreatePayment $command): void
    {
        $paymentId = UUID::fromString($command->getId());
        $invoiceId = UUID::fromString($command->getInvoiceId());
        $amount = Money::fromFloat($command->getAmount(), 'EUR');

        $payment = Payment::create(
            $paymentId,
            $invoiceId,
            $command->getTransactionId(),
            $amount,
            $command->getMethod()
        );

        $this->paymentRepository->save($payment);
    }
}

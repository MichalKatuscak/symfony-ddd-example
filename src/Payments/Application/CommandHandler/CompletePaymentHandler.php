<?php

declare(strict_types=1);

namespace Payments\Application\CommandHandler;

use InvalidArgumentException;
use Payments\Application\Command\CompletePayment;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
final readonly class CompletePaymentHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository,
        private MessageBusInterface $eventBus
    ) {
    }

    public function __invoke(CompletePayment $command): void
    {
        $paymentId = UUID::fromString($command->getId());
        $payment = $this->paymentRepository->findById($paymentId);

        if ($payment === null) {
            throw new InvalidArgumentException('Payment not found');
        }

        $payment->markAsCompleted();
        $this->paymentRepository->save($payment);

        // Dispatch domain events
        foreach ($payment->releaseEvents() as $event) {
            $this->eventBus->dispatch($event);
        }
    }
}

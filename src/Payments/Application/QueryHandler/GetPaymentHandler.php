<?php

declare(strict_types=1);

namespace Payments\Application\QueryHandler;

use InvalidArgumentException;
use Payments\Application\Query\GetPayment;
use Payments\Application\ReadModel\PaymentReadModel;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class GetPaymentHandler
{
    public function __construct(
        private PaymentRepository $paymentRepository
    ) {
    }

    public function __invoke(GetPayment $query): PaymentReadModel
    {
        $paymentId = UUID::fromString($query->getId());
        $payment = $this->paymentRepository->findById($paymentId);

        if ($payment === null) {
            throw new InvalidArgumentException('Payment not found');
        }

        return PaymentReadModel::fromEntity($payment);
    }
}

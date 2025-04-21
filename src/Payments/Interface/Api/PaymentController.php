<?php

declare(strict_types=1);

namespace Payments\Interface\Api;

use Payments\Application\Command\CompletePayment;
use Payments\Application\Query\GetPayment;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/payments')]
class PaymentController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
        private SerializerInterface $serializer,
        private PaymentRepository $paymentRepository
    ) {
    }

    #[Route('/{id}', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $query = new GetPayment($id);
        $payment = $this->queryBus->dispatch($query);

        return new JsonResponse(
            $this->serializer->serialize($payment, 'json', ['groups' => 'payment_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/{id}/complete', methods: ['POST'])]
    public function complete(string $id): JsonResponse
    {
        $command = new CompletePayment($id);
        $this->commandBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // In a real application, this would use a query to get a list of payments
        // For simplicity, we're returning an empty array
        return new JsonResponse(
            $this->serializer->serialize([], 'json', ['groups' => 'payment_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/invoice/{invoiceId}', methods: ['GET'], name: 'api_payment_by_invoice')]
    public function getByInvoiceId(string $invoiceId): JsonResponse
    {
        $payment = $this->paymentRepository->findByInvoiceId(UUID::fromString($invoiceId));

        if ($payment === null) {
            return new JsonResponse(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }

        $query = new GetPayment($payment->getId()->getValue());
        $paymentReadModel = $this->queryBus->dispatch($query);

        return new JsonResponse(
            $this->serializer->serialize($paymentReadModel, 'json', ['groups' => 'payment_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

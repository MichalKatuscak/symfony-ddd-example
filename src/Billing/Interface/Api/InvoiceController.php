<?php

declare(strict_types=1);

namespace Billing\Interface\Api;

use Billing\Application\Command\IssueInvoice;
use Billing\Application\Query\GetInvoice;
use Billing\Domain\Repository\InvoiceRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/invoices')]
class InvoiceController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
        private SerializerInterface $serializer,
        private InvoiceRepository $invoiceRepository
    ) {
    }

    #[Route('/{id}', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $query = new GetInvoice($id);
        $invoice = $this->queryBus->dispatch($query);

        return new JsonResponse(
            $this->serializer->serialize($invoice, 'json', ['groups' => 'invoice_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/{id}/issue', methods: ['POST'])]
    public function issue(string $id): JsonResponse
    {
        $command = new IssueInvoice($id);
        $this->commandBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // In a real application, this would use a query to get a list of invoices
        // For simplicity, we're returning an empty array
        return new JsonResponse(
            $this->serializer->serialize([], 'json', ['groups' => 'invoice_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/order/{orderId}', methods: ['GET'], name: 'api_invoice_by_order')]
    public function getByOrderId(string $orderId): JsonResponse
    {
        $invoice = $this->invoiceRepository->findByOrderId(UUID::fromString($orderId));

        if ($invoice === null) {
            return new JsonResponse(['message' => 'Invoice not found'], Response::HTTP_NOT_FOUND);
        }

        $query = new GetInvoice($invoice->getId()->getValue());
        $invoiceReadModel = $this->queryBus->dispatch($query);

        return new JsonResponse(
            $this->serializer->serialize($invoiceReadModel, 'json', ['groups' => 'invoice_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

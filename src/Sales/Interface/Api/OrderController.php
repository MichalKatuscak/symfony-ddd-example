<?php

declare(strict_types=1);

namespace Sales\Interface\Api;

use Infrastructure\Api\ApiRequestValidator;
use Sales\Application\Command\CreateOrder;
use Sales\Application\Command\PlaceOrder;
use Sales\Application\Query\GetOrder;
use Sales\Domain\ValueObject\UUID;
use Sales\Interface\Api\Request\CreateOrderRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/orders')]
class OrderController
{
    public function __construct(
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus,
        private SerializerInterface $serializer,
        private ApiRequestValidator $validator
    ) {
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        // Deserialize and validate the request
        $createOrderRequest = $this->serializer->deserialize(
            $request->getContent(),
            CreateOrderRequest::class,
            'json'
        );

        $validationResponse = $this->validator->validate($request, $createOrderRequest);
        if ($validationResponse !== null) {
            return $validationResponse;
        }

        // Generate a new UUID for the order
        $orderId = UUID::generate()->getValue();

        // Create and dispatch the command
        $commandData = $createOrderRequest->toCommand($orderId);
        $command = new CreateOrder(
            $commandData['id'],
            $commandData['customerEmail'],
            $commandData['customerName'],
            $commandData['items']
        );

        $this->commandBus->dispatch($command);

        return new JsonResponse(['id' => $orderId], Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function get(string $id): JsonResponse
    {
        $query = new GetOrder($id);
        $order = $this->queryBus->dispatch($query);

        return new JsonResponse(
            $this->serializer->serialize($order, 'json', ['groups' => 'order_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }

    #[Route('/{id}/place', methods: ['POST'])]
    public function place(string $id): JsonResponse
    {
        $command = new PlaceOrder($id);
        $this->commandBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    #[Route('', methods: ['GET'])]
    public function list(): JsonResponse
    {
        // In a real application, this would use a query to get a list of orders
        // For simplicity, we're returning an empty array
        return new JsonResponse(
            $this->serializer->serialize([], 'json', ['groups' => 'order_read']),
            Response::HTTP_OK,
            [],
            true
        );
    }
}

<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private HalResponseFormatter $halResponseFormatter
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => ['onKernelView', 0],
        ];
    }

    public function onKernelView(ViewEvent $event): void
    {
        $request = $event->getRequest();
        
        // Only handle API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        $result = $event->getControllerResult();
        
        // Skip if a Response is already set
        if ($result instanceof Response) {
            return;
        }

        // Format the response based on the result type
        $resourceType = $this->getResourceType($result);
        $data = $result;

        if ($resourceType !== null) {
            $data = $this->halResponseFormatter->format($result, $resourceType);
        }

        $json = $this->serializer->serialize($data, 'json', [
            'json_encode_options' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        ]);

        $response = new JsonResponse($json, 200, [], true);
        $response->headers->set('Content-Type', 'application/hal+json');
        
        $event->setResponse($response);
    }

    private function getResourceType(mixed $result): ?string
    {
        if (is_object($result)) {
            $className = get_class($result);
            
            if (str_contains($className, 'OrderReadModel')) {
                return 'order';
            }
            
            if (str_contains($className, 'InvoiceReadModel')) {
                return 'invoice';
            }
            
            if (str_contains($className, 'PaymentReadModel')) {
                return 'payment';
            }
        }

        return null;
    }
}

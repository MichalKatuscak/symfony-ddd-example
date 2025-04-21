<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => ['onKernelException', 0],
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $request = $event->getRequest();
        
        // Only handle exceptions for API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        $exception = $event->getThrowable();
        $statusCode = $this->getStatusCode($exception);
        $data = [
            'status' => $statusCode,
            'message' => $exception->getMessage(),
        ];

        if ($statusCode >= 500) {
            // Log server errors
            error_log($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
            
            // In production, don't expose internal errors
            if ($_ENV['APP_ENV'] === 'prod') {
                $data['message'] = 'Internal Server Error';
            }
        }

        $event->setResponse(new JsonResponse($data, $statusCode));
    }

    private function getStatusCode(\Throwable $exception): int
    {
        if ($exception instanceof HttpExceptionInterface) {
            return $exception->getStatusCode();
        }

        return match (get_class($exception)) {
            InvalidArgumentException::class => 400,
            default => 500,
        };
    }
}

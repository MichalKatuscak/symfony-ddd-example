<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiLogSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private LoggerInterface $logger
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', -10],
            KernelEvents::RESPONSE => ['onKernelResponse', -10],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        
        // Only log API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Skip API documentation
        if (str_starts_with($request->getPathInfo(), '/api/doc')) {
            return;
        }

        // Log the request
        $this->logger->info('API Request', [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'query' => $request->query->all(),
            'client_ip' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ]);
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();
        
        // Only log API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Skip API documentation
        if (str_starts_with($request->getPathInfo(), '/api/doc')) {
            return;
        }

        // Log the response
        $context = [
            'method' => $request->getMethod(),
            'path' => $request->getPathInfo(),
            'status_code' => $response->getStatusCode(),
            'duration' => microtime(true) - $request->server->get('REQUEST_TIME_FLOAT'),
        ];

        // Log errors with more detail
        if ($response->getStatusCode() >= 400) {
            $this->logger->error('API Error Response', $context);
        } else {
            $this->logger->info('API Response', $context);
        }
    }
}

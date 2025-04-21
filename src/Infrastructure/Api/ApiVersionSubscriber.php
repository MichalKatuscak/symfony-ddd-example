<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiVersionSubscriber implements EventSubscriberInterface
{
    private const SUPPORTED_VERSIONS = ['1.0', '1.1'];
    private const DEFAULT_VERSION = '1.0';

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 30],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        
        // Only handle API routes
        if (!str_starts_with($request->getPathInfo(), '/api')) {
            return;
        }

        // Skip API documentation
        if (str_starts_with($request->getPathInfo(), '/api/doc')) {
            return;
        }

        // Get the requested API version
        $version = $request->headers->get('X-API-Version', self::DEFAULT_VERSION);
        
        // Check if the requested version is supported
        if (!in_array($version, self::SUPPORTED_VERSIONS, true)) {
            $response = new JsonResponse([
                'status' => 400,
                'message' => sprintf(
                    'Unsupported API version "%s". Supported versions are: %s',
                    $version,
                    implode(', ', self::SUPPORTED_VERSIONS)
                ),
            ], 400);
            
            $event->setResponse($response);
            return;
        }
        
        // Store the API version in the request attributes
        $request->attributes->set('api_version', $version);
    }
}

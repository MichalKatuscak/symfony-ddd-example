<?php

declare(strict_types=1);

namespace Infrastructure\Api;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HalResponseFormatter
{
    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        private RequestStack $requestStack
    ) {
    }

    public function format(object $resource, string $resourceType): array
    {
        $data = $this->convertToArray($resource);
        $data['_links'] = $this->generateLinks($resource, $resourceType);

        return $data;
    }

    /**
     * @return array<string, mixed>
     */
    private function convertToArray(object $resource): array
    {
        $data = [];
        $reflection = new \ReflectionClass($resource);
        $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $methodName = $method->getName();
            if (str_starts_with($methodName, 'get') && $methodName !== 'getLinks') {
                $propertyName = lcfirst(substr($methodName, 3));
                $data[$propertyName] = $method->invoke($resource);
            }
        }

        return $data;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function generateLinks(object $resource, string $resourceType): array
    {
        $links = [
            'self' => [
                'href' => $this->generateSelfLink($resource, $resourceType),
            ],
        ];

        switch ($resourceType) {
            case 'order':
                $links = array_merge($links, $this->generateOrderLinks($resource));
                break;
            case 'invoice':
                $links = array_merge($links, $this->generateInvoiceLinks($resource));
                break;
            case 'payment':
                $links = array_merge($links, $this->generatePaymentLinks($resource));
                break;
        }

        return $links;
    }

    private function generateSelfLink(object $resource, string $resourceType): string
    {
        $getId = 'getId';
        $route = "api_{$resourceType}_get";

        return $this->urlGenerator->generate($route, [
            'id' => $resource->$getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function generateOrderLinks(object $order): array
    {
        $links = [];

        // Add link to invoice if order is placed
        if ($order->getStatus() === 'placed') {
            $links['invoice'] = [
                'href' => $this->urlGenerator->generate('api_invoice_by_order', [
                    'orderId' => $order->getId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }

        return $links;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function generateInvoiceLinks(object $invoice): array
    {
        $links = [
            'order' => [
                'href' => $this->urlGenerator->generate('api_order_get', [
                    'id' => $invoice->getOrderId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
        ];

        // Add link to payment if invoice is issued
        if ($invoice->getStatus() === 'issued' || $invoice->getStatus() === 'paid') {
            $links['payment'] = [
                'href' => $this->urlGenerator->generate('api_payment_by_invoice', [
                    'invoiceId' => $invoice->getId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ];
        }

        return $links;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function generatePaymentLinks(object $payment): array
    {
        return [
            'invoice' => [
                'href' => $this->urlGenerator->generate('api_invoice_get', [
                    'id' => $payment->getInvoiceId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
            ],
        ];
    }
}

<?php

declare(strict_types=1);

namespace Tests\Behat;

use Behat\Behat\Context\Context;
use Billing\Domain\Repository\InvoiceRepository;
use Sales\Domain\ValueObject\UUID;

class InvoiceContext implements Context
{
    public function __construct(
        private InvoiceRepository $invoiceRepository
    ) {
    }

    /**
     * @Then an invoice should be created for the order
     */
    public function anInvoiceShouldBeCreatedForTheOrder(): void
    {
        $orderId = UUID::fromString($this->getOrderContext()->getOrderId());
        $invoice = $this->invoiceRepository->findByOrderId($orderId);
        
        if ($invoice === null) {
            throw new \RuntimeException('No invoice found for the order');
        }
    }

    /**
     * @Then the invoice should be in :status status
     */
    public function theInvoiceShouldBeInStatus(string $status): void
    {
        $orderId = UUID::fromString($this->getOrderContext()->getOrderId());
        $invoice = $this->invoiceRepository->findByOrderId($orderId);
        
        if ($invoice === null) {
            throw new \RuntimeException('No invoice found for the order');
        }
        
        if ($invoice->getStatus() !== $status) {
            throw new \RuntimeException(
                sprintf('Invoice status is "%s", but "%s" expected.', $invoice->getStatus(), $status)
            );
        }
    }

    private function getOrderContext(): OrderContext
    {
        return $this->getContext(OrderContext::class);
    }

    private function getContext(string $contextClass)
    {
        return $this->kernel->getContainer()->get($contextClass);
    }
}

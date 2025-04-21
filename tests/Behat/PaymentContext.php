<?php

declare(strict_types=1);

namespace Tests\Behat;

use Behat\Behat\Context\Context;
use Billing\Domain\Repository\InvoiceRepository;
use Payments\Application\Command\CompletePayment;
use Payments\Domain\Repository\PaymentRepository;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Messenger\MessageBusInterface;

class PaymentContext implements Context
{
    private ?string $paymentId = null;

    public function __construct(
        private InvoiceRepository $invoiceRepository,
        private PaymentRepository $paymentRepository,
        private MessageBusInterface $commandBus
    ) {
    }

    /**
     * @Then a payment should be created for the invoice
     */
    public function aPaymentShouldBeCreatedForTheInvoice(): void
    {
        $orderId = UUID::fromString($this->getOrderContext()->getOrderId());
        $invoice = $this->invoiceRepository->findByOrderId($orderId);
        
        if ($invoice === null) {
            throw new \RuntimeException('No invoice found for the order');
        }
        
        $payment = $this->paymentRepository->findByInvoiceId($invoice->getId());
        
        if ($payment === null) {
            throw new \RuntimeException('No payment found for the invoice');
        }
        
        $this->paymentId = $payment->getId()->getValue();
    }

    /**
     * @Then the payment should be in :status status
     */
    public function thePaymentShouldBeInStatus(string $status): void
    {
        if ($this->paymentId === null) {
            $this->aPaymentShouldBeCreatedForTheInvoice();
        }
        
        $paymentId = UUID::fromString($this->paymentId);
        $payment = $this->paymentRepository->findById($paymentId);
        
        if ($payment === null) {
            throw new \RuntimeException('Payment not found');
        }
        
        if ($payment->getStatus() !== $status) {
            throw new \RuntimeException(
                sprintf('Payment status is "%s", but "%s" expected.', $payment->getStatus(), $status)
            );
        }
    }

    /**
     * @When I complete the payment
     */
    public function iCompleteThePayment(): void
    {
        if ($this->paymentId === null) {
            $this->aPaymentShouldBeCreatedForTheInvoice();
        }
        
        $command = new CompletePayment($this->paymentId);
        $this->commandBus->dispatch($command);
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

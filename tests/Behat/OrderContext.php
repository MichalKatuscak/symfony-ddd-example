<?php

declare(strict_types=1);

namespace Tests\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Sales\Application\Command\CreateOrder;
use Sales\Application\Command\PlaceOrder;
use Sales\Application\Query\GetOrder;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class OrderContext implements Context
{
    private ?string $orderId = null;
    private ?Response $response = null;

    public function __construct(
        private KernelInterface $kernel,
        private MessageBusInterface $commandBus,
        private MessageBusInterface $queryBus
    ) {
    }

    /**
     * @Given I have a new order with the following items:
     */
    public function iHaveANewOrderWithTheFollowingItems(TableNode $table): void
    {
        $items = [];
        foreach ($table->getHash() as $row) {
            $items[] = [
                'productName' => $row['product'],
                'unitPrice' => (float) $row['price'],
                'quantity' => (int) $row['quantity']
            ];
        }

        $this->orderId = UUID::generate()->getValue();
        
        $command = new CreateOrder(
            $this->orderId,
            'customer@example.com',
            'John Doe',
            $items
        );

        $this->commandBus->dispatch($command);
    }

    /**
     * @When I place the order
     */
    public function iPlaceTheOrder(): void
    {
        $command = new PlaceOrder($this->orderId);
        $this->commandBus->dispatch($command);
    }

    /**
     * @When I request the order details
     */
    public function iRequestTheOrderDetails(): void
    {
        $query = new GetOrder($this->orderId);
        $this->order = $this->queryBus->dispatch($query);
    }

    /**
     * @Then the order should be in :status status
     */
    public function theOrderShouldBeInStatus(string $status): void
    {
        $query = new GetOrder($this->orderId);
        $order = $this->queryBus->dispatch($query);
        
        if ($order->getStatus() !== $status) {
            throw new \RuntimeException(
                sprintf('Order status is "%s", but "%s" expected.', $order->getStatus(), $status)
            );
        }
    }

    /**
     * @Then the order total should be :total
     */
    public function theOrderTotalShouldBe(string $total): void
    {
        $query = new GetOrder($this->orderId);
        $order = $this->queryBus->dispatch($query);
        
        $expectedTotal = (float) $total;
        $actualTotal = $order->getTotal();
        
        if (abs($actualTotal - $expectedTotal) > 0.01) {
            throw new \RuntimeException(
                sprintf('Order total is %.2f, but %.2f expected.', $actualTotal, $expectedTotal)
            );
        }
    }
}

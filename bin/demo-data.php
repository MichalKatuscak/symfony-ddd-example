#!/usr/bin/env php
<?php

require dirname(__DIR__).'/vendor/autoload.php';

use Sales\Application\Command\CreateOrder;
use Sales\Application\Command\PlaceOrder;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\MessageBusInterface;

$output = new ConsoleOutput();
$output->writeln('Loading demo data...');

$kernel = new \App\Kernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

/** @var ContainerInterface $container */
$container = $kernel->getContainer();

/** @var MessageBusInterface $commandBus */
$commandBus = $container->get('command.bus');

// Create and place orders
$orderIds = createOrders($commandBus, $output);

// Place orders
placeOrders($commandBus, $orderIds, $output);

$output->writeln('Demo data loaded successfully!');

/**
 * @return string[]
 */
function createOrders(MessageBusInterface $commandBus, ConsoleOutput $output): array
{
    $orderIds = [];

    // Order 1
    $orderId1 = UUID::generate()->getValue();
    $orderIds[] = $orderId1;
    
    $command1 = new CreateOrder(
        $orderId1,
        'customer1@example.com',
        'John Doe',
        [
            [
                'productName' => 'Laptop',
                'unitPrice' => 1299.99,
                'quantity' => 1
            ],
            [
                'productName' => 'Mouse',
                'unitPrice' => 49.99,
                'quantity' => 1
            ]
        ]
    );
    
    $commandBus->dispatch($command1);
    $output->writeln("Created order: {$orderId1}");

    // Order 2
    $orderId2 = UUID::generate()->getValue();
    $orderIds[] = $orderId2;
    
    $command2 = new CreateOrder(
        $orderId2,
        'customer2@example.com',
        'Jane Smith',
        [
            [
                'productName' => 'Smartphone',
                'unitPrice' => 899.99,
                'quantity' => 1
            ],
            [
                'productName' => 'Phone Case',
                'unitPrice' => 19.99,
                'quantity' => 1
            ],
            [
                'productName' => 'Screen Protector',
                'unitPrice' => 9.99,
                'quantity' => 2
            ]
        ]
    );
    
    $commandBus->dispatch($command2);
    $output->writeln("Created order: {$orderId2}");

    // Order 3
    $orderId3 = UUID::generate()->getValue();
    $orderIds[] = $orderId3;
    
    $command3 = new CreateOrder(
        $orderId3,
        'customer3@example.com',
        'Bob Johnson',
        [
            [
                'productName' => 'Headphones',
                'unitPrice' => 199.99,
                'quantity' => 1
            ]
        ]
    );
    
    $commandBus->dispatch($command3);
    $output->writeln("Created order: {$orderId3}");

    return $orderIds;
}

function placeOrders(MessageBusInterface $commandBus, array $orderIds, ConsoleOutput $output): void
{
    foreach ($orderIds as $orderId) {
        $command = new PlaceOrder($orderId);
        $commandBus->dispatch($command);
        $output->writeln("Placed order: {$orderId}");
    }
}

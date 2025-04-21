<?php

namespace App\Command;

use Sales\Application\Command\CreateOrder;
use Sales\Application\Command\PlaceOrder;
use Sales\Domain\ValueObject\UUID;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:create-order',
    description: 'Creates a new order',
)]
class CreateOrderCommand extends Command
{
    public function __construct(
        private MessageBusInterface $commandBus
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Customer email')
            ->addArgument('name', InputArgument::REQUIRED, 'Customer name')
            ->addOption('place', null, InputOption::VALUE_NONE, 'Place the order immediately')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $name = $input->getArgument('name');

        $orderId = UUID::generate()->getValue();

        // Create a sample order
        $createOrder = new CreateOrder(
            $orderId,
            $email,
            $name,
            [
                [
                    'productName' => 'Sample Product',
                    'unitPrice' => 99.99,
                    'quantity' => 1
                ]
            ]
        );

        $this->commandBus->dispatch($createOrder);
        $io->success("Order created with ID: {$orderId}");

        // Place the order if requested
        if ($input->getOption('place')) {
            $placeOrder = new PlaceOrder($orderId);
            $this->commandBus->dispatch($placeOrder);
            $io->success("Order placed");
        }

        return Command::SUCCESS;
    }
}

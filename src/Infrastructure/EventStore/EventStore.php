<?php

declare(strict_types=1);

namespace Infrastructure\EventStore;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use ReflectionClass;
use Sales\Domain\ValueObject\UUID;

class EventStore
{
    public function __construct(
        private Connection $connection
    ) {
    }

    public function append(object $event, UUID $aggregateId, string $aggregateType): void
    {
        $eventType = (new ReflectionClass($event))->getShortName();
        $eventData = $this->serializeEvent($event);
        $occurredAt = new DateTimeImmutable();

        $this->connection->insert('event_store', [
            'aggregate_id' => $aggregateId->getValue(),
            'aggregate_type' => $aggregateType,
            'event_type' => $eventType,
            'event_data' => json_encode($eventData),
            'occurred_at' => $occurredAt->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @return array<object>
     */
    public function getEventsForAggregate(UUID $aggregateId): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from('event_store')
            ->where('aggregate_id = :aggregateId')
            ->setParameter('aggregateId', $aggregateId->getValue())
            ->orderBy('id', 'ASC');

        $events = [];
        $rows = $queryBuilder->executeQuery()->fetchAllAssociative();

        foreach ($rows as $row) {
            $eventData = json_decode($row['event_data'], true);
            $eventClass = $this->getEventClass($row['event_type']);
            $events[] = $this->deserializeEvent($eventClass, $eventData);
        }

        return $events;
    }

    private function serializeEvent(object $event): array
    {
        $reflection = new ReflectionClass($event);
        $properties = $reflection->getProperties();
        $data = [];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $value = $property->getValue($event);

            if ($value instanceof UUID) {
                $value = $value->getValue();
            } elseif ($value instanceof DateTimeImmutable) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $data[$property->getName()] = $value;
        }

        return $data;
    }

    private function deserializeEvent(string $eventClass, array $eventData): object
    {
        $reflection = new ReflectionClass($eventClass);
        $constructor = $reflection->getConstructor();
        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $paramName = $parameter->getName();
            $paramType = $parameter->getType()->getName();
            $value = $eventData[$paramName] ?? null;

            if ($paramType === UUID::class) {
                $value = UUID::fromString($value);
            } elseif ($paramType === DateTimeImmutable::class) {
                $value = new DateTimeImmutable($value);
            }

            $args[] = $value;
        }

        return $reflection->newInstanceArgs($args);
    }

    private function getEventClass(string $eventType): string
    {
        $eventMap = [
            'OrderPlaced' => 'Sales\\Domain\\Event\\OrderPlaced',
            'InvoiceIssued' => 'Billing\\Domain\\Event\\InvoiceIssued',
            'PaymentReceived' => 'Payments\\Domain\\Event\\PaymentReceived',
        ];

        return $eventMap[$eventType] ?? throw new \InvalidArgumentException("Unknown event type: {$eventType}");
    }
}

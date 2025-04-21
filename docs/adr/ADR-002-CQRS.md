# ADR-002: CQRS and Event Sourcing

## Status

Accepted

## Context

Our system needs to handle complex business processes across multiple domains (Sales, Billing, Payments). We need to:

1. Maintain a clear separation between read and write operations
2. Ensure consistency across domains when state changes
3. Provide an audit trail of all changes
4. Support eventual consistency for cross-domain operations
5. Optimize read operations for performance

## Decision

We will implement Command Query Responsibility Segregation (CQRS) with a light version of Event Sourcing:

1. **Command Query Responsibility Segregation (CQRS)**:
   - Commands: Represent intentions to change the system state (e.g., CreateOrder, PlaceOrder)
   - Queries: Represent requests for information without changing state (e.g., GetOrder)
   - Command Handlers: Process commands and apply changes to the domain model
   - Query Handlers: Process queries and return read models
   - Read Models: Optimized representations of data for reading

2. **Event Sourcing (Light Version)**:
   - Domain Events: Represent facts that have happened in the system (e.g., OrderPlaced, InvoiceIssued)
   - Event Storage: Events are stored in a Doctrine table (not a full event store like Kafka)
   - Event Handlers: Process domain events and update read models or trigger side effects

3. **Implementation Details**:
   - Symfony Messenger for command and event buses
   - Command Bus: Synchronous processing
   - Event Bus: Asynchronous processing with Doctrine transport
   - Aggregates emit domain events when state changes
   - Event handlers update read models and trigger cross-domain processes

## Consequences

### Positive

1. **Separation of Concerns**: Clear separation between read and write operations
2. **Scalability**: Read and write sides can be scaled independently
3. **Flexibility**: Read models can be optimized for specific use cases
4. **Auditability**: All state changes are recorded as events
5. **Cross-Domain Communication**: Events provide a clean way for domains to communicate

### Negative

1. **Complexity**: More complex than a traditional CRUD architecture
2. **Eventual Consistency**: Read models may be temporarily out of sync with the write model
3. **Code Duplication**: Separate models for reading and writing can lead to some duplication
4. **Development Overhead**: More code is required for the same functionality

### Mitigations

1. **Simplified Event Sourcing**: Using a light version of Event Sourcing reduces complexity
2. **Clear Documentation**: Documenting the flow of commands and events helps developers understand the system
3. **Automated Tests**: Comprehensive tests ensure that the system behaves correctly
4. **Monitoring**: Monitoring event processing helps detect and resolve inconsistencies

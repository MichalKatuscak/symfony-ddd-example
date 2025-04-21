# DDD Monorepo Project

A Domain-Driven Design monorepo for Sales, Billing, and Payments domains using Symfony 6.3 and PHP 8.1.

## Architecture

This project follows a Domain-Driven Design (DDD) architecture with the following layers:
- **Domain**: Contains the business logic, entities, value objects, and domain events
- **Application**: Contains application services, command/query handlers, and DTOs
- **Infrastructure**: Contains implementations of repositories, external services, and persistence
- **Interface**: Contains API controllers, console commands, and other interfaces

The project also implements:
- CQRS (Command Query Responsibility Segregation)
- Event Sourcing (light version)
- Hexagonal Architecture (Ports and Adapters)

## Bounded Contexts

The project is divided into three bounded contexts:
- **Sales**: Handles orders and order items
- **Billing**: Handles invoices and invoice items
- **Payments**: Handles payment processing and transactions

## Local Development

### Prerequisites

- Docker and Docker Compose
- PHP 8.1+
- Composer

### Setup

1. Clone the repository
2. Run `make setup` to install dependencies, start Docker containers, and load demo data

### Running the Application

- API: http://localhost:8080
- MailHog: http://localhost:8025
- PostgreSQL: localhost:5432 (user: app, password: app, database: app)

### Commands

```bash
# Start Docker containers
make up

# Stop Docker containers
make down

# Run tests
make test

# Run Behat tests
make behat

# Fix code style
make cs-fix

# Run static analysis
make analyze

# Load demo data
make demo-data
```

## Testing

- Unit tests: `make test`
- BDD tests: `make behat`
- Code quality: `make analyze`

## Documentation

- API documentation is available at `/api/doc`
- Architecture Decision Records (ADRs) are in the `docs/adr` directory
- UML diagrams are in the `docs/uml` directory

## CI/CD

GitHub Actions are used for continuous integration:
- Code style checking
- Static analysis
- Unit and integration tests
- Docker image building (on main branch)

.PHONY: up down install test cs-fix phpstan psalm analyze behat demo-data

# Docker commands
up:
	docker-compose -f docker/docker-compose.yml up -d

down:
	docker-compose -f docker/docker-compose.yml down

# Composer commands
install:
	composer install

# Testing commands
test:
	php vendor/bin/phpunit

behat:
	php vendor/bin/behat

# Code quality commands
cs-fix:
	php vendor/bin/php-cs-fixer fix

phpstan:
	php vendor/bin/phpstan analyse

psalm:
	php vendor/bin/psalm

analyze: cs-fix phpstan psalm

# Application commands
demo-data:
	php bin/demo-data.php

# Combined commands
setup: install up demo-data

# Help command
help:
	@echo "Available commands:"
	@echo "  up          - Start Docker containers"
	@echo "  down        - Stop Docker containers"
	@echo "  install     - Install dependencies"
	@echo "  test        - Run PHPUnit tests"
	@echo "  behat       - Run Behat tests"
	@echo "  cs-fix      - Fix code style"
	@echo "  phpstan     - Run PHPStan analysis"
	@echo "  psalm       - Run Psalm analysis"
	@echo "  analyze     - Run all code quality tools"
	@echo "  demo-data   - Load demo data"
	@echo "  setup       - Install dependencies, start containers, and load demo data"

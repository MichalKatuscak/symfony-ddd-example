<?php

use Infrastructure\Doctrine\DoctrineTypeRegistrar;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Load environment variables
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env.test', 'test');

// Register Doctrine types
DoctrineTypeRegistrar::registerTypes();

// Create test database if it doesn't exist
try {
    $connection = new \PDO(
        'pgsql:host=postgres;port=5432',
        'app',
        'app'
    );

    try {
        $connection->exec('CREATE DATABASE app_test');
        echo "Created test database\n";
    } catch (\PDOException $e) {
        // Database already exists
        echo "Test database already exists\n";
    }
} catch (\PDOException $e) {
    echo "Warning: Could not connect to database server: " . $e->getMessage() . "\n";
    echo "Tests requiring database connection may fail\n";
}

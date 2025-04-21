<?php

use Infrastructure\Doctrine\DoctrineTypeRegistrar;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

// Load environment variables
(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

// Register Doctrine types
DoctrineTypeRegistrar::registerTypes();

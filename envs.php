<?php

// Load environment variable for API Key
require __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Now you can access environment variables using getenv() or $_ENV
define("API_KEY", $_ENV['API_KEY']);

?>
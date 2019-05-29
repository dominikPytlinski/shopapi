<?php

use src\classes\Router;

/**
 * 
 * Require and load vlucas/phpdotenv for .env file
 * 
 */
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

require 'src/config.php';

/**
 * 
 * Require autoload.php file from src
 * 
 */
require $GLOBALS['path']['autoload'];

$api = new Router();
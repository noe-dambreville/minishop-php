<?php
require_once __DIR__ . '/../../vendor/autoload.php';

try {
    $dotenv = \Dotenv\Dotenv::createImmutable(__DIR__, '../../.env');
    $dotenv->safeLoad();
} catch (Exception) {}

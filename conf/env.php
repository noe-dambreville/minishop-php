<?php
require_once '../vendor/autoload.php';

try {
    $dotenv = \Dotenv\Dotenv::createImmutable('../.env');
    $dotenv->safeLoad();
} catch (Exception) {}

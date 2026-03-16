<?php
// public/register.php
require_once __DIR__ . '/../config/bootstrap.php';

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processRegister();
} else {
    $controller->showRegister();
}

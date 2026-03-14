<?php
// public/login.php
require_once __DIR__ . '/../config/bootstrap.php';

$controller = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->processLogin();
} else {
    $controller->showLogin();
}

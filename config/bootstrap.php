<?php
// config/bootstrap.php

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/app.php';

// Autoloader simples para classes em src/
spl_autoload_register(function (string $class): void {
    $base = __DIR__ . '/../src/';
    $subDirs = ['Controllers/', 'Models/', 'Middleware/'];
    foreach ($subDirs as $dir) {
        $file = $base . $dir . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Iniciar sessão com configurações seguras
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => SESSION_LIFETIME,
        'path'     => '/',
        'secure'   => false, // true em produção com HTTPS
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// Verificar expiração de sessão
if (isset($_SESSION['last_activity'])) {
    if (time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
        session_unset();
        session_destroy();
        header('Location: ' . APP_URL . '/login.php?expired=1');
        exit;
    }
}
$_SESSION['last_activity'] = time();

// Helpers globais
function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function flash(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}

function getFlash(string $key): ?string {
    $msg = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user_id']);
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function hasRole(string $role): bool {
    return ($_SESSION['user']['role'] ?? '') === $role;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirect(APP_URL . '/login.php');
    }
}

function requireRole(string $role): void {
    requireLogin();
    if (!hasRole($role)) {
        http_response_code(403);
        include __DIR__ . '/../views/shared/403.php';
        exit;
    }
}

function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function formatDate(?string $date): string {
    if (!$date) return '—';
    return date('d/m/Y H:i', strtotime($date));
}

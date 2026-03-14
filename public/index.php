<?php
// public/index.php
require_once __DIR__ . '/../config/bootstrap.php';

if (isLoggedIn()) {
    $role = currentUser()['role'];
    $routes = [
        ROLE_ALUNO       => APP_URL . '/aluno/dashboard.php',
        ROLE_FUNCIONARIO => APP_URL . '/funcionario/dashboard.php',
        ROLE_GESTOR      => APP_URL . '/gestor/dashboard.php',
    ];
    redirect($routes[$role] ?? APP_URL . '/login.php');
}

redirect(APP_URL . '/login.php');

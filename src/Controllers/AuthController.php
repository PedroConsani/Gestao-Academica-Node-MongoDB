<?php
// src/Controllers/AuthController.php

class AuthController {
    private UtilizadorModel $model;

    public function __construct() {
        $this->model = new UtilizadorModel();
    }

    public function showLogin(): void {
        if (isLoggedIn()) {
            $this->redirectByRole($_SESSION['user']['role']);
        }
        $error = getFlash('error');
        $expired = isset($_GET['expired']);
        include __DIR__ . '/../../views/auth/login.php';
    }

    public function processLogin(): void {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            flash('error', 'Por favor preencha todos os campos.');
            redirect(APP_URL . '/login.php');
        }

        $user = $this->model->findByEmail($email);

        if (!$user || !$this->model->verifyPassword($password, $user['password_hash'])) {
            flash('error', 'Email ou password incorretos.');
            redirect(APP_URL . '/login.php');
        }

        // Regenerar ID de sessão para prevenir session fixation
        session_regenerate_id(true);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user']    = [
            'id'    => $user['id'],
            'nome'  => $user['nome'],
            'email' => $user['email'],
            'role'  => $user['role'],
        ];
        $_SESSION['last_activity'] = time();

        $this->redirectByRole($user['role']);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        redirect(APP_URL . '/login.php');
    }

    private function redirectByRole(string $role): void {
        $routes = [
            ROLE_ALUNO       => APP_URL . '/aluno/dashboard.php',
            ROLE_FUNCIONARIO => APP_URL . '/funcionario/dashboard.php',
            ROLE_GESTOR      => APP_URL . '/gestor/dashboard.php',
        ];
        redirect($routes[$role] ?? APP_URL . '/login.php');
    }
}

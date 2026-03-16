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

    // ── Registo ────────────────────────────────────────────
    public function showRegister(): void {
        if (isLoggedIn()) {
            $this->redirectByRole($_SESSION['user']['role']);
        }
        $errors = [];
        include __DIR__ . '/../../views/auth/register.php';
    }

    public function processRegister(): void {
        $errors = [];

        // Validação servidor
        $v = new Validator($_POST);
        $v->required('nome', 'Nome')
          ->maxLength('nome', 'Nome', 150)
          ->required('email', 'Email')
          ->email('email', 'Email')
          ->maxLength('email', 'Email', 150)
          ->required('password', 'Password')
          ->minLength('password', 'Password', 8)
          ->required('password_confirm', 'Confirmação de Password');

        if (!$v->passes()) {
            $errors = array_values($v->errors());
        }

        // Confirmar passwords
        if (empty($errors) && $_POST['password'] !== $_POST['password_confirm']) {
            $errors[] = 'As passwords não coincidem.';
        }

        // Verificar email único
        if (empty($errors) && $this->model->findByEmail(trim($_POST['email']))) {
            $errors[] = 'Este email já está registado. Por favor inicie sessão.';
        }

        if (!empty($errors)) {
            include __DIR__ . '/../../views/auth/register.php';
            return;
        }

        // Criar utilizador com perfil Aluno
        $userId = $this->model->create(
            trim($_POST['nome']),
            strtolower(trim($_POST['email'])),
            $_POST['password'],
            ROLE_ALUNO
        );

        // Login automático após registo
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
        $_SESSION['user']    = [
            'id'    => $userId,
            'nome'  => trim($_POST['nome']),
            'email' => strtolower(trim($_POST['email'])),
            'role'  => ROLE_ALUNO,
        ];
        $_SESSION['last_activity'] = time();

        flash('success', 'Conta criada com sucesso! Complete a sua ficha de aluno para prosseguir.');
        redirect(APP_URL . '/aluno/dashboard.php');
    }

    // ── Logout ─────────────────────────────────────────────
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

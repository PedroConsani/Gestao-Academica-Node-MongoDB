<?php
// public/gestor/uc-toggle.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$ucModel = new UCModel();
$id      = (int) ($_GET['id'] ?? 0);

if (!$id) {
    redirect(APP_URL . '/gestor/ucs.php');
}

$ucModel->toggleAtivo($id);
flash('success', 'Estado da UC alterado.');
redirect(APP_URL . '/gestor/ucs.php');

<?php
// public/gestor/curso-toggle.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel = new CursoModel();
$id         = (int) ($_GET['id'] ?? 0);

if (!$id) {
    redirect(APP_URL . '/gestor/cursos.php');
}

$cursoModel->toggleAtivo($id);
flash('success', 'Estado do curso alterado.');
redirect(APP_URL . '/gestor/cursos.php');

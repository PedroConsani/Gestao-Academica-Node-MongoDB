<?php
// public/gestor/plano-remover.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$planoModel = new PlanoEstudosModel();
$id         = (int) ($_GET['id'] ?? 0);
$cursoId    = (int) ($_GET['curso_id'] ?? 0);

if ($id) {
    $planoModel->removeUC($id);
    flash('success', 'UC removida do plano de estudos.');
}

redirect(APP_URL . '/gestor/plano-estudos.php?curso_id=' . $cursoId);

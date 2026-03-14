<?php
// public/gestor/plano-estudos.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel  = new CursoModel();
$planoModel  = new PlanoEstudosModel();
$ucModel     = new UCModel();
$errors      = [];

$cursoId = (int) ($_GET['curso_id'] ?? 0);
$curso   = $cursoModel->findById($cursoId);

if (!$curso) {
    flash('error', 'Curso não encontrado.');
    redirect(APP_URL . '/gestor/cursos.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('uc_id', 'UC')
      ->required('ano', 'Ano')
      ->required('semestre', 'Semestre')
      ->inArray('semestre', 'Semestre', ['1', '2']);

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        $ucId     = (int) $_POST['uc_id'];
        $ano      = (int) $_POST['ano'];
        $semestre = (int) $_POST['semestre'];

        if ($ano < 1 || $ano > $curso['duracao_anos']) {
            $errors[] = "O ano deve estar entre 1 e {$curso['duracao_anos']}.";
        } elseif (!$planoModel->addUC($cursoId, $ucId, $ano, $semestre)) {
            $errors[] = 'Esta UC já está associada a este curso.';
        } else {
            flash('success', 'UC adicionada ao plano de estudos.');
            redirect(APP_URL . '/gestor/plano-estudos.php?curso_id=' . $cursoId);
        }
    }
}

$plano          = $planoModel->getByCurso($cursoId);
$ucsDisponiveis = $ucModel->notInCurso($cursoId);

include __DIR__ . '/../../views/gestor/plano-estudos.php';

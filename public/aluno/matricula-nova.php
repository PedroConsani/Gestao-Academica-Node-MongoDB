<?php
// public/aluno/matricula-nova.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$matriculaModel = new MatriculaModel();
$fichaModel     = new FichaAlunoModel();
$cursoModel     = new CursoModel();
$userId         = currentUser()['id'];
$errors         = [];

// Verificar se ficha está aprovada
$ficha = $fichaModel->findByAluno($userId);
if (!$ficha || $ficha['estado'] !== FICHA_APROVADA) {
    flash('error', 'Precisa de ter a ficha de aluno aprovada antes de submeter uma matrícula.');
    redirect(APP_URL . '/aluno/dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('curso_id', 'Curso')
      ->required('ano_letivo', 'Ano Letivo')
      ->maxLength('ano_letivo', 'Ano Letivo', 9);

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        $matriculaModel->create(
            $userId,
            (int) $_POST['curso_id'],
            trim($_POST['ano_letivo']),
            trim($_POST['observacoes'] ?? '')
        );
        flash('success', 'Pedido de matrícula submetido com sucesso!');
        redirect(APP_URL . '/aluno/matriculas.php');
    }
}

$cursos = $cursoModel->all(true);
include __DIR__ . '/../../views/aluno/matricula-nova.php';

<?php
// public/aluno/matricula-nova.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$matriculaModel = new MatriculaModel();
$fichaModel     = new FichaAlunoModel();
$cursoModel     = new CursoModel();
$ucModel        = new UCModel();
$userId         = currentUser()['id'];
$errors         = [];

// Verificar se ficha está aprovada
$ficha = $fichaModel->findByAluno($userId);
if (!$ficha || $ficha['estado'] !== FICHA_APROVADA) {
    flash('error', 'Precisa de ter a ficha de aluno aprovada antes de submeter uma matrícula.');
    redirect(APP_URL . '/aluno/dashboard.php');
}

// Cursos em que o aluno já tem matrícula pendente ou aprovada
$cursosJaMatriculados = $matriculaModel->cursosJaMatriculados($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('curso_id', 'Curso')
      ->required('ano_letivo', 'Ano Letivo')
      ->maxLength('ano_letivo', 'Ano Letivo', 9);

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        $cursoId = (int) $_POST['curso_id'];

        // Verificar duplicação no servidor (mesmo que a view já bloqueie)
        if (in_array($cursoId, $cursosJaMatriculados)) {
            $errors[] = 'Já tem uma matrícula ativa ou pendente neste curso.';
        } else {
            $matriculaModel->create(
                $userId,
                $cursoId,
                trim($_POST['ano_letivo']),
                trim($_POST['observacoes'] ?? '')
            );
            flash('success', 'Pedido de matrícula submetido com sucesso!');
            redirect(APP_URL . '/aluno/matriculas.php');
        }
    }
}

$cursos = $cursoModel->all(true);

// UCModel::getByCurso() retorna: id, nome, codigo, creditos, primeiro_ano, primeiro_semestre
$ucsPorCurso = [];
foreach ($cursos as $c) {
    $ucsPorCurso[$c['id']] = $ucModel->getByCurso($c['id']);
}

include __DIR__ . '/../../views/aluno/matricula-nova.php';
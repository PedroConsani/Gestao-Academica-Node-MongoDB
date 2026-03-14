<?php
// public/funcionario/pauta-nova.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$pautaModel     = new PautaModel();
$cursoModel     = new CursoModel();
$ucModel        = new UCModel();
$matriculaModel = new MatriculaModel();
$planoModel     = new PlanoEstudosModel();
$userId         = currentUser()['id'];
$errors         = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('curso_id', 'Curso')
      ->required('uc_id', 'UC')
      ->required('ano_letivo', 'Ano Letivo')
      ->required('epoca', 'Época')
      ->inArray('epoca', 'Época', EPOCAS);

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        $cursoId   = (int) $_POST['curso_id'];
        $ucId      = (int) $_POST['uc_id'];
        $anoLetivo = trim($_POST['ano_letivo']);
        $epoca     = $_POST['epoca'];

        // Verificar duplicação
        if ($pautaModel->exists($ucId, $cursoId, $anoLetivo, $epoca)) {
            $errors[] = 'Já existe uma pauta para esta UC/curso/ano/época.';
        } else {
            $pautaId = $pautaModel->create($ucId, $cursoId, $anoLetivo, $epoca, $userId);

            // Adicionar alunos com matrícula aprovada automaticamente
            $alunos = $matriculaModel->alunosAprovadosCurso($cursoId, $anoLetivo);
            if ($alunos) {
                $pautaModel->adicionarAlunos($pautaId, array_column($alunos, 'id'));
            }

            flash('success', 'Pauta criada com sucesso' . ($alunos ? ' com ' . count($alunos) . ' aluno(s).' : ' (sem alunos elegíveis).'));
            redirect(APP_URL . '/funcionario/pauta-notas.php?id=' . $pautaId);
        }
    }
}

$cursos = $cursoModel->all(true);
$ucs    = $ucModel->all(true);

// Preparar mapeamento UC por curso para o JS
$ucsPorCurso = [];
foreach ($cursos as $c) {
    $ucsCurso = $planoModel->getUCsByCurso($c['id']);
    $ucsPorCurso[$c['id']] = array_map(fn($u) => [
        'id'     => $u['id'],
        'nome'   => $u['nome'],
        'codigo' => $u['codigo'],
    ], $ucsCurso);
}

include __DIR__ . '/../../views/funcionario/pauta-nova.php';

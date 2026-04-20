<?php
// public/aluno/matriculas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$matriculaModel = new MatriculaModel();
$userId         = currentUser()['id'];
$matriculas     = $matriculaModel->findByAluno($userId);

// Contar UCs por curso (sem carregar os dados das UCs)
$pdo = getDB();
$ucsPorCurso = [];
foreach ($matriculas as $m) {
    $cid = $m['curso_id'];
    if (!isset($ucsPorCurso[$cid])) {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM plano_estudos WHERE curso_id = ?");
        $stmt->execute([$cid]);
        $ucsPorCurso[$cid] = $stmt->fetchColumn();
    }
}

include __DIR__ . '/../../views/aluno/matriculas.php';
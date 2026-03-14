<?php
// public/aluno/dashboard.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$fichaModel    = new FichaAlunoModel();
$matriculaModel = new MatriculaModel();

$userId     = currentUser()['id'];
$ficha      = $fichaModel->findByAluno($userId);
$matriculas = $matriculaModel->findByAluno($userId);

$fichaEstado       = $ficha['estado'] ?? null;
$totalMatriculas   = count($matriculas);
$matriculasAprovadas = count(array_filter($matriculas, fn($m) => $m['estado'] === MATRICULA_APROVADA));

include __DIR__ . '/../../views/aluno/dashboard.php';

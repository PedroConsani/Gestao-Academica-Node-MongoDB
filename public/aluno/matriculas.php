<?php
// public/aluno/matriculas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$matriculaModel = new MatriculaModel();
$userId         = currentUser()['id'];
$matriculas     = $matriculaModel->findByAluno($userId);

include __DIR__ . '/../../views/aluno/matriculas.php';

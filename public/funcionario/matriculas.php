<?php
// public/funcionario/matriculas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$matriculaModel = new MatriculaModel();
$filtro         = $_GET['filtro'] ?? 'todos';

$todas = $matriculaModel->all();

if ($filtro !== 'todos') {
    $matriculas = array_filter($todas, fn($m) => $m['estado'] === $filtro);
    $matriculas = array_values($matriculas);
} else {
    $matriculas = $todas;
}

include __DIR__ . '/../../views/funcionario/matriculas.php';

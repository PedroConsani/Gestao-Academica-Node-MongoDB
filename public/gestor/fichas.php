<?php
// public/gestor/fichas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$fichaModel = new FichaAlunoModel();
$filtro     = $_GET['filtro'] ?? 'todos';

$todas = $fichaModel->allSubmetidas();

if ($filtro !== 'todos') {
    $fichas = array_values(array_filter($todas, fn($f) => $f['estado'] === $filtro));
} else {
    $fichas = $todas;
}

include __DIR__ . '/../../views/gestor/fichas.php';

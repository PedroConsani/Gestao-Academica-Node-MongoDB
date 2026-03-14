<?php
// public/gestor/dashboard.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel = new CursoModel();
$ucModel    = new UCModel();
$fichaModel = new FichaAlunoModel();

$totalCursos = count($cursoModel->all(true));
$totalUCs    = count($ucModel->all(true));

$todasFichas      = $fichaModel->allSubmetidas();
$fichasSubmetidas = count($todasFichas);
$fichasPendentes  = count(array_filter($todasFichas, fn($f) => $f['estado'] === FICHA_SUBMETIDA));

include __DIR__ . '/../../views/gestor/dashboard.php';

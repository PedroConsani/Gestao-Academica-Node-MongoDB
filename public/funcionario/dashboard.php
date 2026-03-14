<?php
// public/funcionario/dashboard.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$matriculaModel = new MatriculaModel();
$pautaModel     = new PautaModel();

$pendentes   = count($matriculaModel->allPendentes());
$totalPautas = count($pautaModel->all());

include __DIR__ . '/../../views/funcionario/dashboard.php';

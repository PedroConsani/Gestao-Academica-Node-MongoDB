<?php
// public/funcionario/pautas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$pautaModel = new PautaModel();
$pautas     = $pautaModel->all();

include __DIR__ . '/../../views/funcionario/pautas.php';

<?php
// public/gestor/cursos.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel = new CursoModel();
$cursos     = $cursoModel->all();

include __DIR__ . '/../../views/gestor/cursos.php';

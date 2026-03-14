<?php
// public/gestor/curso-novo.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel = new CursoModel();
$userId     = currentUser()['id'];
$errors     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('nome', 'Nome')
      ->maxLength('nome', 'Nome', 200)
      ->required('codigo', 'Código')
      ->maxLength('codigo', 'Código', 20)
      ->required('duracao_anos', 'Duração');

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        // Verificar código único
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cursos WHERE codigo = ?");
        $stmt->execute([strtoupper($_POST['codigo'])]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "O código '{$_POST['codigo']}' já existe.";
        } else {
            $cursoModel->create($_POST, $userId);
            flash('success', 'Curso criado com sucesso.');
            redirect(APP_URL . '/gestor/cursos.php');
        }
    }
}

include __DIR__ . '/../../views/gestor/curso-form.php';

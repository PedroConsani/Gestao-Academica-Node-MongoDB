<?php
// public/gestor/uc-nova.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$ucModel = new UCModel();
$userId  = currentUser()['id'];
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $v = new Validator($_POST);
    $v->required('nome', 'Nome')
      ->maxLength('nome', 'Nome', 200)
      ->required('codigo', 'Código')
      ->maxLength('codigo', 'Código', 20)
      ->required('creditos', 'Créditos')
      ->numeric('creditos', 'Créditos')
      ->between('creditos', 'Créditos', 0.5, 30);

    if (!$v->passes()) {
        $errors = array_values($v->errors());
    } else {
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM unidades_curriculares WHERE codigo = ?");
        $stmt->execute([strtoupper($_POST['codigo'])]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "O código '{$_POST['codigo']}' já existe.";
        } else {
            $ucModel->create($_POST, $userId);
            flash('success', 'Unidade Curricular criada com sucesso.');
            redirect(APP_URL . '/gestor/ucs.php');
        }
    }
}

include __DIR__ . '/../../views/gestor/uc-form.php';

<?php
// public/gestor/uc-editar.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$ucModel = new UCModel();
$errors  = [];

$id = (int) ($_GET['id'] ?? 0);
$uc = $ucModel->findById($id);

if (!$uc) {
    flash('error', 'Unidade Curricular não encontrada.');
    redirect(APP_URL . '/gestor/ucs.php');
}

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
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM unidades_curriculares WHERE codigo = ? AND id != ?");
        $stmt->execute([strtoupper($_POST['codigo']), $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "O código '{$_POST['codigo']}' já pertence a outra UC.";
        } else {
            $ucModel->update($id, $_POST);
            flash('success', 'UC atualizada com sucesso.');
            redirect(APP_URL . '/gestor/ucs.php');
        }
    }

    $uc = array_merge($uc, $_POST);
}

include __DIR__ . '/../../views/gestor/uc-form.php';

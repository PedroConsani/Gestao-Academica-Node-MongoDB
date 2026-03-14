<?php
// public/gestor/curso-editar.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$cursoModel = new CursoModel();
$errors     = [];

$id    = (int) ($_GET['id'] ?? 0);
$curso = $cursoModel->findById($id);

if (!$curso) {
    flash('error', 'Curso não encontrado.');
    redirect(APP_URL . '/gestor/cursos.php');
}

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
        // Verificar código único (excluindo este curso)
        $pdo  = getDB();
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM cursos WHERE codigo = ? AND id != ?");
        $stmt->execute([strtoupper($_POST['codigo']), $id]);
        if ($stmt->fetchColumn() > 0) {
            $errors[] = "O código '{$_POST['codigo']}' já pertence a outro curso.";
        } else {
            $cursoModel->update($id, $_POST);
            flash('success', 'Curso atualizado com sucesso.');
            redirect(APP_URL . '/gestor/cursos.php');
        }
    }

    // Recarregar dados atualizados para o formulário
    $curso = array_merge($curso, $_POST);
}

include __DIR__ . '/../../views/gestor/curso-form.php';

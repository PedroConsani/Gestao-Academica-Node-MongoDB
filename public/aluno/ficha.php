<?php
// public/aluno/ficha.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$fichaModel = new FichaAlunoModel();
$cursoModel = new CursoModel();
$userId     = currentUser()['id'];
$errors     = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? 'guardar';

    // Validação
    $v = new Validator($_POST);
    $v->required('data_nascimento', 'Data de Nascimento')
      ->date('data_nascimento', 'Data de Nascimento')
      ->required('nacionalidade', 'Nacionalidade')
      ->required('nif', 'NIF')
      ->required('telefone', 'Telefone')
      ->required('morada', 'Morada')
      ->required('codigo_postal', 'Código Postal')
      ->required('localidade', 'Localidade')
      ->required('curso_id', 'Curso');

    // Upload foto
    $fotoPath = null;
    if (!empty($_FILES['foto']['name'])) {
        $upload = UploadHelper::processarFoto($_FILES['foto']);
        if (!$upload['success']) {
            $errors[] = $upload['error'];
        } else {
            $fotoPath = $upload['path'];
        }
    }

    if (!$v->passes()) {
        $errors = array_merge($errors, array_values($v->errors()));
    }

    if (empty($errors)) {
        $data = array_merge($_POST, ['foto_path' => $fotoPath]);
        $fichaModel->createOrUpdate($userId, $data);

        if ($acao === 'submeter') {
            $fichaModel->submeter($userId);
            flash('success', 'Ficha submetida com sucesso!');
            redirect(APP_URL . '/aluno/dashboard.php');
        }

        flash('success', 'Ficha guardada como rascunho.');
        redirect(APP_URL . '/aluno/ficha.php');
    }
}

$ficha  = $fichaModel->findByAluno($userId);
$cursos = $cursoModel->all(true);

include __DIR__ . '/../../views/aluno/ficha.php';

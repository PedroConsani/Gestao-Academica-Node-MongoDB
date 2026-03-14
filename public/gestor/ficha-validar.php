<?php
// public/gestor/ficha-validar.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$fichaModel = new FichaAlunoModel();
$userId     = currentUser()['id'];
$errors     = [];

$id    = (int) ($_GET['id'] ?? 0);
$ficha = $fichaModel->findById($id);

if (!$ficha) {
    flash('error', 'Ficha não encontrada.');
    redirect(APP_URL . '/gestor/fichas.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ficha['estado'] === FICHA_SUBMETIDA) {
    $decisao = $_POST['decisao'] ?? '';

    if (!in_array($decisao, [FICHA_APROVADA, FICHA_REJEITADA])) {
        $errors[] = 'Decisão inválida.';
    } else {
        $ok = $fichaModel->validar(
            $id,
            $decisao,
            trim($_POST['observacoes'] ?? ''),
            $userId
        );

        if ($ok) {
            flash('success', 'Decisão registada com sucesso.');
            redirect(APP_URL . '/gestor/fichas.php');
        } else {
            $errors[] = 'Não foi possível registar a decisão. A ficha pode já ter sido tratada.';
        }
    }

    $ficha = $fichaModel->findById($id);
}

include __DIR__ . '/../../views/gestor/ficha-validar.php';

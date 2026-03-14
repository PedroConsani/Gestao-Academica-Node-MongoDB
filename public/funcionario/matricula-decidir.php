<?php
// public/funcionario/matricula-decidir.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$matriculaModel = new MatriculaModel();
$userId         = currentUser()['id'];
$errors         = [];

$id       = (int) ($_GET['id'] ?? 0);
$matricula = $matriculaModel->findById($id);

if (!$matricula) {
    flash('error', 'Pedido de matrícula não encontrado.');
    redirect(APP_URL . '/funcionario/matriculas.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $matricula['estado'] === MATRICULA_PENDENTE) {
    $decisao = $_POST['decisao'] ?? '';

    if (!in_array($decisao, [MATRICULA_APROVADA, MATRICULA_REJEITADA])) {
        $errors[] = 'Decisão inválida.';
    } else {
        $ok = $matriculaModel->decidir(
            $id,
            $decisao,
            trim($_POST['observacoes'] ?? ''),
            $userId
        );

        if ($ok) {
            flash('success', 'Decisão registada com sucesso.');
            redirect(APP_URL . '/funcionario/matriculas.php');
        } else {
            $errors[] = 'Não foi possível registar a decisão. O pedido pode já ter sido tratado.';
        }
    }

    // Recarregar após POST com erro
    $matricula = $matriculaModel->findById($id);
}

include __DIR__ . '/../../views/funcionario/matricula-decidir.php';

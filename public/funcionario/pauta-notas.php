<?php
// public/funcionario/pauta-notas.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_FUNCIONARIO);

$pautaModel = new PautaModel();
$userId     = currentUser()['id'];

$id    = (int) ($_GET['id'] ?? 0);
$pauta = $pautaModel->findById($id);

if (!$pauta) {
    flash('error', 'Pauta não encontrada.');
    redirect(APP_URL . '/funcionario/pautas.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$pauta['fechada']) {
    $acao = $_POST['acao'] ?? 'guardar';

    if ($acao === 'fechar') {
        $pautaModel->fecharPauta($id);
        flash('success', 'Pauta fechada com sucesso.');
        redirect(APP_URL . '/funcionario/pautas.php');
    }

    // Guardar notas
    $notasPost = $_POST['notas'] ?? [];
    foreach ($notasPost as $alunoId => $nota) {
        $notaVal = ($nota !== '' && $nota !== null) ? (float) $nota : null;

        // Validar intervalo 0-20
        if ($notaVal !== null && ($notaVal < 0 || $notaVal > 20)) {
            flash('error', "Nota inválida para o aluno #$alunoId. Deve estar entre 0 e 20.");
            redirect(APP_URL . '/funcionario/pauta-notas.php?id=' . $id);
        }

        $pautaModel->registarNota($id, (int) $alunoId, $notaVal, $userId);
    }

    flash('success', 'Notas guardadas com sucesso.');
    redirect(APP_URL . '/funcionario/pauta-notas.php?id=' . $id);
}

$notas = $pautaModel->getNotas($id);
include __DIR__ . '/../../views/funcionario/pauta-notas.php';

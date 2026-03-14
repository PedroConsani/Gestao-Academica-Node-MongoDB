<?php // views/gestor/ficha-validar.php
$pageTitle = 'Validar Ficha';
ob_start(); ?>

<div class="page-header">
    <h1>🔍 Ficha de <?= e($ficha['aluno_nome']) ?></h1>
    <a href="<?= APP_URL ?>/gestor/fichas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Dados Pessoais</div>
        <?php if ($ficha['foto_path']): ?>
            <img src="<?= e(\UploadHelper::fotoUrl($ficha['foto_path'])) ?>" class="foto-preview" style="margin-bottom:1rem;">
        <?php endif; ?>
        <p><strong>Nome:</strong> <?= e($ficha['aluno_nome']) ?></p>
        <p><strong>Email:</strong> <?= e($ficha['aluno_email']) ?></p>
        <p><strong>Nascimento:</strong> <?= $ficha['data_nascimento'] ? date('d/m/Y', strtotime($ficha['data_nascimento'])) : '—' ?></p>
        <p><strong>Nacionalidade:</strong> <?= e($ficha['nacionalidade'] ?? '—') ?></p>
        <p><strong>NIF:</strong> <?= e($ficha['nif'] ?? '—') ?></p>
        <p><strong>CC:</strong> <?= e($ficha['cc'] ?? '—') ?></p>
        <p><strong>Telefone:</strong> <?= e($ficha['telefone'] ?? '—') ?></p>
        <p><strong>Morada:</strong> <?= e($ficha['morada'] ?? '—') ?>, <?= e($ficha['codigo_postal'] ?? '') ?> <?= e($ficha['localidade'] ?? '') ?></p>
        <p><strong>Curso:</strong> <?= $ficha['curso_nome'] ? e($ficha['curso_nome']) : '<em>Não selecionado</em>' ?></p>
    </div>

    <div class="card">
        <div class="card-title">Estado da Ficha</div>
        <p><strong>Estado:</strong> <span class="badge badge-<?= $ficha['estado'] ?>"><?= ucfirst($ficha['estado']) ?></span></p>
        <p><strong>Submetida em:</strong> <?= formatDate($ficha['submetida_em']) ?></p>
        <?php if ($ficha['validada_por']): ?>
            <p><strong>Validada por:</strong> <?= e($ficha['validada_por_nome']) ?></p>
            <p><strong>Validada em:</strong> <?= formatDate($ficha['validada_em']) ?></p>
        <?php endif; ?>
        <?php if ($ficha['observacoes']): ?>
            <div class="alert alert-info" style="margin-top:.75rem;">
                <strong>Observações:</strong> <?= e($ficha['observacoes']) ?>
            </div>
        <?php endif; ?>

        <?php if ($ficha['estado'] === 'submetida'): ?>
            <hr style="margin:1rem 0;border-color:var(--border)">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= APP_URL ?>/gestor/ficha-validar.php?id=<?= $ficha['id'] ?>">
                <div class="form-group">
                    <label>Observações / Justificação</label>
                    <textarea name="observacoes" rows="3" placeholder="Motivo da decisão..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
                </div>
                <div class="actions">
                    <button type="submit" name="decisao" value="aprovada" class="btn btn-success"
                        onclick="return confirm('Confirma a aprovação desta ficha?')">✅ Aprovar</button>
                    <button type="submit" name="decisao" value="rejeitada" class="btn btn-danger"
                        onclick="return confirm('Confirma a rejeição desta ficha?')">❌ Rejeitar</button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

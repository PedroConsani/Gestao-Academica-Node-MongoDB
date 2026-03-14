<?php // views/aluno/dashboard.php
$pageTitle = 'Dashboard do Aluno';
ob_start(); ?>

<div class="page-header">
    <h1>👋 Bem-vindo, <?= e(currentUser()['nome']) ?></h1>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num"><?= $fichaEstado ? ucfirst($fichaEstado) : '—' ?></div>
        <div class="stat-label">Estado da Ficha</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $totalMatriculas ?></div>
        <div class="stat-label">Matrículas Submetidas</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $matriculasAprovadas ?></div>
        <div class="stat-label">Matrículas Aprovadas</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📋 Ficha de Aluno</div>
        <?php if ($ficha): ?>
            <p>Estado: <span class="badge badge-<?= $ficha['estado'] ?>"><?= ucfirst($ficha['estado']) ?></span></p>
            <?php if ($ficha['observacoes']): ?>
                <div class="alert alert-info" style="margin-top:.75rem;">
                    <strong>Observação:</strong> <?= e($ficha['observacoes']) ?>
                </div>
            <?php endif; ?>
            <div class="actions" style="margin-top:1rem;">
                <a href="<?= APP_URL ?>/aluno/ficha.php" class="btn btn-primary btn-sm">Ver / Editar</a>
                <?php if (in_array($ficha['estado'], ['rascunho','rejeitada'])): ?>
                    <a href="<?= APP_URL ?>/aluno/ficha-submeter.php" class="btn btn-success btn-sm">Submeter</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p style="color:var(--text-muted);">Ainda não preencheu a sua ficha.</p>
            <a href="<?= APP_URL ?>/aluno/ficha.php" class="btn btn-primary btn-sm" style="margin-top:.75rem;">Preencher Ficha</a>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-title">🎓 Matrículas / Inscrições</div>
        <?php if ($matriculas): ?>
            <?php foreach (array_slice($matriculas, 0, 3) as $m): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;padding:.5rem 0;border-bottom:1px solid var(--border);">
                    <div>
                        <strong><?= e($m['curso_nome']) ?></strong><br>
                        <small style="color:var(--text-muted)"><?= e($m['ano_letivo']) ?></small>
                    </div>
                    <span class="badge badge-<?= $m['estado'] ?>"><?= ucfirst($m['estado']) ?></span>
                </div>
            <?php endforeach; ?>
            <a href="<?= APP_URL ?>/aluno/matriculas.php" class="btn btn-secondary btn-sm" style="margin-top:.75rem;">Ver todas</a>
        <?php else: ?>
            <p style="color:var(--text-muted);">Nenhuma matrícula submetida.</p>
        <?php endif; ?>
        <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary btn-sm" style="margin-top:.75rem;">Nova Matrícula</a>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

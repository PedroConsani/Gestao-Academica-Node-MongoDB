<?php // views/funcionario/dashboard.php
$pageTitle = 'Dashboard — Serviços Académicos';
ob_start(); ?>

<div class="page-header">
    <h1>🏛️ Serviços Académicos</h1>
    <span style="color:var(--text-muted)"><?= e(currentUser()['nome']) ?></span>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num"><?= $pendentes ?></div>
        <div class="stat-label">Matrículas Pendentes</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $totalPautas ?></div>
        <div class="stat-label">Pautas Criadas</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">📋 Pedidos de Matrícula</div>
        <p style="color:var(--text-muted);margin-bottom:1rem;"><?= $pendentes ?> pedido(s) pendente(s) de análise.</p>
        <div class="actions">
            <a href="<?= APP_URL ?>/funcionario/matriculas.php?filtro=pendente" class="btn btn-primary btn-sm">Ver Pendentes</a>
            <a href="<?= APP_URL ?>/funcionario/matriculas.php" class="btn btn-secondary btn-sm">Ver Todos</a>
        </div>
    </div>

    <div class="card">
        <div class="card-title">📊 Pautas de Avaliação</div>
        <p style="color:var(--text-muted);margin-bottom:1rem;"><?= $totalPautas ?> pauta(s) registada(s).</p>
        <div class="actions">
            <a href="<?= APP_URL ?>/funcionario/pautas.php" class="btn btn-primary btn-sm">Ver Pautas</a>
            <a href="<?= APP_URL ?>/funcionario/pauta-nova.php" class="btn btn-success btn-sm">+ Nova Pauta</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

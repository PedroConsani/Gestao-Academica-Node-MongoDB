<?php // views/gestor/dashboard.php
$pageTitle = 'Dashboard — Gestão Pedagógica';
ob_start(); ?>

<div class="page-header">
    <h1>📚 Gestão Pedagógica</h1>
    <span style="color:var(--text-muted)"><?= e(currentUser()['nome']) ?></span>
</div>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-num"><?= $totalCursos ?></div>
        <div class="stat-label">Cursos</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $totalUCs ?></div>
        <div class="stat-label">Unidades Curriculares</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $fichasSubmetidas ?></div>
        <div class="stat-label">Fichas Submetidas</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $fichasPendentes ?></div>
        <div class="stat-label">Fichas por Validar</div>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">🎓 Cursos e Planos de Estudo</div>
        <div class="actions" style="flex-wrap:wrap;">
            <a href="<?= APP_URL ?>/gestor/cursos.php" class="btn btn-primary btn-sm">Gerir Cursos</a>
            <a href="<?= APP_URL ?>/gestor/curso-novo.php" class="btn btn-success btn-sm">+ Novo Curso</a>
            <a href="<?= APP_URL ?>/gestor/ucs.php" class="btn btn-secondary btn-sm">Gerir UCs</a>
            <a href="<?= APP_URL ?>/gestor/uc-nova.php" class="btn btn-success btn-sm">+ Nova UC</a>
        </div>
    </div>

    <div class="card">
        <div class="card-title">📋 Fichas de Alunos</div>
        <p style="color:var(--text-muted);margin-bottom:1rem;"><?= $fichasPendentes ?> ficha(s) aguardam validação.</p>
        <div class="actions">
            <a href="<?= APP_URL ?>/gestor/fichas.php?filtro=submetida" class="btn btn-primary btn-sm">Validar Fichas</a>
            <a href="<?= APP_URL ?>/gestor/fichas.php" class="btn btn-secondary btn-sm">Ver Todas</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

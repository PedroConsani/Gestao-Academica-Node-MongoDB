<?php // views/aluno/dashboard.php
$pageTitle = 'Dashboard do Aluno';
$totalNotas   = $totalNotas   ?? 0;
$ultimasNotas = $ultimasNotas ?? [];
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
    <div class="stat-card">
        <div class="stat-num"><?= $totalNotas  ?></div>
        <div class="stat-label">Notas Lançadas</div>
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

<div class="card">
    <div class="card-title">📊 Últimas Notas</div>
    <?php if ($ultimasNotas): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>UC</th><th>Época</th><th>Nota</th><th>Situação</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimasNotas as $n): ?>
                    <tr>
                        <td><?= e($n['uc_codigo']) ?> — <?= e($n['uc_nome']) ?></td>
                        <td><?= e($n['epoca']) ?></td>
                        <td><strong><?= $n['nota_final'] !== null ? number_format($n['nota_final'], 1) : '—' ?></strong></td>
                        <td>
                            <?php if ($n['nota_final'] === null): ?>
                                <span class="badge badge-rascunho">Por lançar</span>
                            <?php elseif ($n['nota_final'] >= 10): ?>
                                <span class="badge badge-aprovada">✔ Aprovado</span>
                            <?php else: ?>
                                <span class="badge badge-rejeitada">✘ Reprovado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="<?= APP_URL ?>/aluno/notas.php" class="btn btn-secondary btn-sm" style="margin-top:.75rem;">Ver todas as notas</a>
    <?php else: ?>
        <p style="color:var(--text-muted);">Ainda não existem notas lançadas para si.</p>
        <a href="<?= APP_URL ?>/aluno/notas.php" class="btn btn-secondary btn-sm" style="margin-top:.75rem;">Ver pauta completa</a>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
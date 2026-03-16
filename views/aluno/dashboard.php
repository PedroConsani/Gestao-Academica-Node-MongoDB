<?php // views/aluno/dashboard.php
$pageTitle    = 'Dashboard';
$totalNotas   = $totalNotas   ?? 0;
$ultimasNotas = $ultimasNotas ?? [];
ob_start(); ?>

<!-- PAGE HEADER -->
<div class="page-header">
    <div>
        <p>Portal Académico do Aluno</p>
        <h1>Bem-vindo, <?= e(currentUser()['nome']) ?></h1>
    </div>
</div>

<!-- STATS -->
<div class="stat-grid">
    <div class="stat-card">
        <div style="font-size:1.4rem;margin-bottom:.5rem;">📋</div>
        <div class="stat-num" style="font-size:1.5rem;"><?= $fichaEstado ? ucfirst($fichaEstado) : '—' ?></div>
        <div class="stat-label">Estado da Ficha</div>
    </div>
    <div class="stat-card">
        <div style="font-size:1.4rem;margin-bottom:.5rem;">📝</div>
        <div class="stat-num"><?= $totalMatriculas ?></div>
        <div class="stat-label">Matrículas Submetidas</div>
    </div>
    <div class="stat-card">
        <div style="font-size:1.4rem;margin-bottom:.5rem;">✅</div>
        <div class="stat-num"><?= $matriculasAprovadas ?></div>
        <div class="stat-label">Matrículas Aprovadas</div>
    </div>
    <div class="stat-card">
        <div style="font-size:1.4rem;margin-bottom:.5rem;">🎯</div>
        <div class="stat-num"><?= $totalNotas ?></div>
        <div class="stat-label">Notas Lançadas</div>
    </div>
</div>

<!-- CARDS PRINCIPAIS -->
<div class="grid-2">

    <!-- Ficha de Aluno -->
    <div class="card">
        <div class="card-title">📋 Ficha de Aluno</div>
        <?php if ($ficha): ?>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.1rem;">
                <span style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);">Estado atual</span>
                <span class="badge badge-<?= $ficha['estado'] ?>"><?= ucfirst($ficha['estado']) ?></span>
            </div>
            <?php if ($ficha['observacoes']): ?>
                <div class="alert alert-info" style="margin-bottom:1.1rem;">
                    <strong>Observação do Gestor:</strong><br>
                    <?= e($ficha['observacoes']) ?>
                </div>
            <?php endif; ?>
            <div class="actions">
                <a href="<?= APP_URL ?>/aluno/ficha.php" class="btn btn-primary btn-sm">Ver / Editar</a>
                <?php if (in_array($ficha['estado'], ['rascunho', 'rejeitada'])): ?>
                    <a href="<?= APP_URL ?>/aluno/ficha.php" class="btn btn-secondary btn-sm">Submeter</a>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:2rem 0;">
                <div style="font-size:3rem;margin-bottom:.75rem;opacity:.4;">📄</div>
                <p style="color:var(--text-muted);margin-bottom:1.25rem;font-size:.95rem;">
                    Ainda não preencheu a sua ficha de aluno.
                </p>
                <a href="<?= APP_URL ?>/aluno/ficha.php" class="btn btn-primary">Preencher Ficha</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Matrículas -->
    <div class="card">
        <div class="card-title">🎓 Matrículas / Inscrições</div>
        <?php if ($matriculas): ?>
            <?php foreach (array_slice($matriculas, 0, 3) as $m): ?>
                <div style="display:flex;justify-content:space-between;align-items:center;
                            padding:.75rem 0;border-bottom:1px solid var(--border);">
                    <div>
                        <strong style="font-size:.92rem;"><?= e($m['curso_nome']) ?></strong><br>
                        <small style="color:var(--text-muted);font-size:.78rem;letter-spacing:.03em;"><?= e($m['ano_letivo']) ?></small>
                    </div>
                    <span class="badge badge-<?= $m['estado'] ?>"><?= ucfirst($m['estado']) ?></span>
                </div>
            <?php endforeach; ?>
            <div class="actions" style="margin-top:1.1rem;">
                <a href="<?= APP_URL ?>/aluno/matriculas.php" class="btn btn-secondary btn-sm">Ver todas</a>
                <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary btn-sm">+ Nova Matrícula</a>
            </div>
        <?php else: ?>
            <div style="text-align:center;padding:2rem 0;">
                <div style="font-size:3rem;margin-bottom:.75rem;opacity:.4;">🎓</div>
                <p style="color:var(--text-muted);margin-bottom:1.25rem;font-size:.95rem;">
                    Nenhuma matrícula submetida.
                </p>
                <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary">Nova Matrícula</a>
            </div>
        <?php endif; ?>
    </div>

</div>

<!-- ÚLTIMAS NOTAS -->
<div class="card">
    <div class="card-title" style="justify-content:space-between;">
        <span>📊 Últimas Notas</span>
        <a href="<?= APP_URL ?>/aluno/notas.php" class="btn btn-secondary btn-sm">Ver todas →</a>
    </div>

    <?php if ($ultimasNotas): ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Unidade Curricular</th>
                        <th>Época</th>
                        <th>Nota</th>
                        <th>Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ultimasNotas as $n): ?>
                    <tr>
                        <td>
                            <strong style="color:var(--crimson);font-size:.78rem;letter-spacing:.05em;text-transform:uppercase;"><?= e($n['uc_codigo']) ?></strong>
                            <span style="color:var(--text-muted);margin:0 .35rem;">·</span>
                            <?= e($n['uc_nome']) ?>
                        </td>
                        <td><span class="badge badge-rascunho"><?= e($n['epoca']) ?></span></td>
                        <td>
                            <strong style="font-size:1.1rem;font-family:'Playfair Display',serif;">
                                <?= $n['nota_final'] !== null ? number_format($n['nota_final'], 1) : '—' ?>
                            </strong>
                        </td>
                        <td>
                            <?php if ($n['nota_final'] === null): ?>
                                <span class="badge badge-rascunho">Por lançar</span>
                            <?php elseif ($n['nota_final'] >= 10): ?>
                                <span class="badge badge-aprovada">Aprovado</span>
                            <?php else: ?>
                                <span class="badge badge-rejeitada">Reprovado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div style="text-align:center;padding:2.5rem 0;">
            <div style="font-size:3rem;margin-bottom:.75rem;opacity:.3;">📊</div>
            <p style="color:var(--text-muted);font-size:.95rem;">
                Ainda não existem notas lançadas para si.
            </p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

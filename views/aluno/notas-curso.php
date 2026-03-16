<?php // views/aluno/notas-curso.php
$pageTitle = $curso['nome'] . ' — Notas';
ob_start(); ?>

<div class="page-header">
    <h1>📊 Notas - <?= e($curso['nome']) ?></h1>
    <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Dashboard</a>
</div>

<?php if (empty($ucs)): ?>
    <div class="card">
        <p style="color:var(--text-muted);text-align:center;padding:3rem;">Curso sem plano curricular definido.</p>
    </div>
<?php else: ?>
    <div class="card">
        <div class="stat-grid" style="margin-bottom:1.5rem;">
            <div class="stat-card">
                <div class="stat-num"><?= count($ucs) ?></div>
                <div class="stat-label">UCs do curso</div>
            </div>
            <div class="stat-card">
                <div class="stat-num"><?= count($notasRaw) ?></div>
                <div class="stat-label">Notas lançadas</div>
            </div>
        </div>
        
        <?php foreach ($ucs as $i => $uc): ?>
            <?php $notasUC = $notasPorUC[$i] ?? []; ?>
            <div class="uc-section mb-4">
                <div class="uc-header">
                    <h3><?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?></h3>
                    <div class="uc-meta">
                        <span><?= $uc['creditos'] ?> ECTS</span>
                        <span>A<?= $uc['primeiro_ano'] ?> / S<?= $uc['primeiro_semestre'] ?></span>
                    </div>
                </div>
                
                <?php if (empty($notasUC)): ?>
                    <p style="color:var(--text-muted);padding:1.5rem 0;">Sem notas lançadas nesta UC.</p>
                <?php else: ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Ano Letivo</th>
                                    <th>Época</th>
                                    <th>Nota</th>
                                    <th>Situação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($notasUC as $nota): ?>
                                    <tr>
                                        <td><?= e($nota['ano_letivo']) ?></td>
                                        <td><strong><?= e($nota['epoca']) ?></strong></td>
                                        <td>
                                            <?= $nota['nota_final'] !== null 
                                                ? '<strong>' . number_format($nota['nota_final'], 1) . '</strong>'
                                                : '<span style="color:var(--text-muted)">Por lançar</span>' ?>
                                        </td>
                                        <td>
                                            <?php if ($nota['nota_final'] === null): ?>
                                                <span class="badge badge-rascunho">Por lançar</span>
                                            <?php elseif ($nota['nota_final'] >= 10): ?>
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
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>


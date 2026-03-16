<?php // views/aluno/notas.php
$pageTitle = 'As Minhas Notas';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Portal Académico do Aluno</p>
        <h1>As Minhas Notas</h1>
    </div>
    <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (empty($notas)): ?>
    <div class="card" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;opacity:.3;">📊</div>
        <p style="color:var(--text-muted);">Ainda não existem notas lançadas para si.</p>
    </div>
<?php else: ?>

<?php
$agrupado = [];
foreach ($notas as $n) {
    $agrupado[$n['curso_nome']][$n['ano_letivo']][] = $n;
}
$comNota   = array_filter($notas, fn($n) => $n['nota_final'] !== null);
$aprovadas = array_filter($comNota, fn($n) => $n['nota_final'] >= 10);
$reprov    = array_filter($comNota, fn($n) => $n['nota_final'] < 10);
$media     = count($comNota) ? array_sum(array_column(array_values($comNota),'nota_final')) / count($comNota) : null;
?>

<!-- Resumo -->
<div class="stat-grid" style="grid-template-columns:repeat(4,1fr);">
    <div class="stat-card">
        <div class="stat-num"><?= count($comNota) ?></div>
        <div class="stat-label">Notas lançadas</div>
    </div>
    <div class="stat-card">
        <div class="stat-num" style="color:var(--success);"><?= count($aprovadas) ?></div>
        <div class="stat-label">Aprovações</div>
    </div>
    <div class="stat-card">
        <div class="stat-num" style="color:var(--danger);"><?= count($reprov) ?></div>
        <div class="stat-label">Reprovações</div>
    </div>
    <div class="stat-card">
        <div class="stat-num"><?= $media !== null ? number_format($media, 1) : '—' ?></div>
        <div class="stat-label">Média geral</div>
    </div>
</div>

<!-- Notas agrupadas -->
<?php foreach ($agrupado as $cursoNome => $anos): ?>
    <div class="card">
        <div class="card-title"><?= e($cursoNome) ?></div>
        <?php foreach ($anos as $anoLetivo => $ucs): ?>
            <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;
                        color:var(--text-muted);margin-bottom:.75rem;">
                Ano Letivo <?= e($anoLetivo) ?>
            </div>
            <div class="table-wrap" style="margin-bottom:1.5rem;">
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
                        <?php foreach ($ucs as $n): ?>
                        <tr>
                            <td>
                                <strong style="color:var(--crimson);font-size:.75rem;text-transform:uppercase;letter-spacing:.05em;">
                                    <?= e($n['uc_codigo']) ?>
                                </strong>
                                <span style="color:var(--text-muted);margin:0 .3rem;">·</span>
                                <?= e($n['uc_nome']) ?>
                            </td>
                            <td><span class="badge badge-rascunho"><?= e($n['epoca']) ?></span></td>
                            <td>
                                <strong style="font-size:1.1rem;font-family:'Playfair Display',serif;">
                                    <?= $n['nota_final'] !== null ? number_format($n['nota_final'],1) : '—' ?>
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
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

<?php // views/aluno/notas.php
$pageTitle = 'As Minhas Notas';
ob_start(); ?>

<div class="page-header">
    <h1>📊 As Minhas Notas</h1>
    <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (empty($notas)): ?>
    <div class="card">
        <p style="color:var(--text-muted);text-align:center;padding:2rem 0;">
            Ainda não existem notas lançadas para si.
        </p>
    </div>
<?php else: ?>

<?php
// Agrupar por curso → ano letivo → UC
$agrupado = [];
foreach ($notas as $n) {
    $agrupado[$n['curso_nome']][$n['ano_letivo']][] = $n;
}
?>

<?php foreach ($agrupado as $cursoNome => $anos): ?>
    <div class="card">
        <div class="card-title">🎓 <?= e($cursoNome) ?></div>

        <?php foreach ($anos as $anoLetivo => $ucs): ?>
            <h3 style="font-size:.95rem;color:var(--text-muted);margin:.75rem 0 .5rem;">
                Ano Letivo: <?= e($anoLetivo) ?>
            </h3>

            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>UC</th>
                            <th>Época</th>
                            <th>Nota</th>
                            <th>Situação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ucs as $n): ?>
                        <tr>
                            <td>
                                <strong><?= e($n['uc_codigo']) ?></strong>
                                — <?= e($n['uc_nome']) ?>
                            </td>
                            <td><?= e($n['epoca']) ?></td>
                            <td>
                                <?php if ($n['nota_final'] !== null): ?>
                                    <strong style="font-size:1.05rem;"><?= number_format($n['nota_final'], 1) ?></strong>
                                <?php else: ?>
                                    <span style="color:var(--text-muted)">Sem nota</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                if ($n['nota_final'] === null) {
                                    echo '<span class="badge badge-rascunho">Por lançar</span>';
                                } elseif ($n['nota_final'] >= 10) {
                                    echo '<span class="badge badge-aprovada">✔ Aprovado</span>';
                                } else {
                                    echo '<span class="badge badge-rejeitada">✘ Reprovado</span>';
                                }
                                ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>

<!-- Resumo estatístico -->
<?php
$comNota    = array_filter($notas, fn($n) => $n['nota_final'] !== null);
$aprovadas  = array_filter($comNota, fn($n) => $n['nota_final'] >= 10);
$reprovadas = array_filter($comNota, fn($n) => $n['nota_final'] < 10);
$media      = count($comNota) ? array_sum(array_column(iterator_to_array((function() use ($comNota) { foreach($comNota as $n) yield $n; })()), 'nota_final')) / count($comNota) : null;
?>

<div class="card">
    <div class="card-title">📈 Resumo</div>
    <div class="stat-grid" style="margin-bottom:0;">
        <div class="stat-card">
            <div class="stat-num"><?= count($comNota) ?></div>
            <div class="stat-label">Notas lançadas</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:var(--success)"><?= count($aprovadas) ?></div>
            <div class="stat-label">Aprovações</div>
        </div>
        <div class="stat-card">
            <div class="stat-num" style="color:var(--danger)"><?= count($reprovadas) ?></div>
            <div class="stat-label">Reprovações</div>
        </div>
        <div class="stat-card">
            <div class="stat-num"><?= $media !== null ? number_format($media, 1) : '—' ?></div>
            <div class="stat-label">Média geral</div>
        </div>
    </div>
</div>

<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
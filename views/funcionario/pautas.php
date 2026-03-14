<?php // views/funcionario/pautas.php
$pageTitle = 'Pautas de Avaliação';
ob_start(); ?>

<div class="page-header">
    <h1>📊 Pautas de Avaliação</h1>
    <div class="actions">
        <a href="<?= APP_URL ?>/funcionario/pauta-nova.php" class="btn btn-success btn-sm">+ Nova Pauta</a>
        <a href="<?= APP_URL ?>/funcionario/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($pautas)): ?>
    <div class="card"><p style="color:var(--text-muted);text-align:center;padding:2rem">Nenhuma pauta criada.</p></div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>UC</th>
                    <th>Curso</th>
                    <th>Ano Letivo</th>
                    <th>Época</th>
                    <th>Estado</th>
                    <th>Criada em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pautas as $p): ?>
                <tr>
                    <td><strong><?= e($p['uc_codigo']) ?></strong> — <?= e($p['uc_nome']) ?></td>
                    <td><?= e($p['curso_nome']) ?></td>
                    <td><?= e($p['ano_letivo']) ?></td>
                    <td><?= e($p['epoca']) ?></td>
                    <td>
                        <?php if ($p['fechada']): ?>
                            <span class="badge badge-aprovada">Fechada</span>
                        <?php else: ?>
                            <span class="badge badge-submetida">Aberta</span>
                        <?php endif; ?>
                    </td>
                    <td><?= formatDate($p['criada_em']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/funcionario/pauta-notas.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Ver / Notas</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

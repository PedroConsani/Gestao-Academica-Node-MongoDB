<?php // views/aluno/matriculas.php
$pageTitle = 'As Minhas Matrículas';
ob_start(); ?>

<div class="page-header">
    <h1>🎓 Matrículas / Inscrições</h1>
    <div class="actions">
        <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary btn-sm">+ Nova Matrícula</a>
        <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($matriculas)): ?>
    <div class="card">
        <p style="color:var(--text-muted);text-align:center;padding:2rem 0;">Ainda não submeteu nenhum pedido de matrícula.</p>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th>Ano Letivo</th>
                        <th>Estado</th>
                        <th>Observações</th>
                        <th>Data Decisão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($matriculas as $m): ?>
                        <tr>
                            <td><?= e($m['curso_nome']) ?></td>
                            <td><?= e($m['ano_letivo']) ?></td>
                            <td><span class="badge badge-<?= $m['estado'] ?>"><?= ucfirst($m['estado']) ?></span></td>
                            <td><?= $m['observacoes_func'] ? e($m['observacoes_func']) : '<span style="color:var(--text-muted)">—</span>' ?></td>
                            <td><?= formatDate($m['decidido_em']) ?></td>
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

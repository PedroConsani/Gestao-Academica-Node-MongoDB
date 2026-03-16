<?php // views/funcionario/matriculas.php
$pageTitle = 'Gestão de Matrículas';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Serviços Académicos</p>
        <h1>Pedidos de Matrícula</h1>
    </div>
    <a href="<?= APP_URL ?>/funcionario/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<!-- Filtros -->
<div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:1.5rem;">
    <?php foreach (['todos'=>'Todos','pendente'=>'Pendentes','aprovada'=>'Aprovados','rejeitada'=>'Rejeitados'] as $val => $label): ?>
        <a href="?filtro=<?= $val ?>"
           class="btn btn-sm <?= $filtro === $val ? 'btn-primary' : 'btn-secondary' ?>">
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if (empty($matriculas)): ?>
    <div class="card" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;opacity:.3;margin-bottom:1rem;">📋</div>
        <p style="color:var(--text-muted);">Nenhum pedido encontrado.</p>
    </div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Aluno</th><th>Curso</th><th>Ano Letivo</th>
                    <th>Estado</th><th>Submetido em</th><th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $m): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:.82rem;"><?= $m['id'] ?></td>
                    <td>
                        <strong style="font-size:.92rem;"><?= e($m['aluno_nome']) ?></strong><br>
                        <small style="color:var(--text-muted);"><?= e($m['aluno_email']) ?></small>
                    </td>
                    <td><?= e($m['curso_nome']) ?></td>
                    <td><?= e($m['ano_letivo']) ?></td>
                    <td><span class="badge badge-<?= $m['estado'] ?>"><?= ucfirst($m['estado']) ?></span></td>
                    <td style="font-size:.82rem;color:var(--text-muted);"><?= formatDate($m['criado_em']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/funcionario/matricula-decidir.php?id=<?= $m['id'] ?>"
                           class="btn btn-sm <?= $m['estado'] === 'pendente' ? 'btn-primary' : 'btn-secondary' ?>">
                           <?= $m['estado'] === 'pendente' ? 'Analisar' : 'Ver' ?>
                        </a>
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

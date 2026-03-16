<?php // views/funcionario/matriculas.php
$pageTitle = 'Gestão de Matrículas';
ob_start(); ?>

<div class="page-header">
    <h1>🎓 Pedidos de Matrícula</h1>
    <a href="<?= APP_URL ?>/funcionario/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<!-- Filtros -->
<div class="card" style="padding:.75rem 1rem;">
    <div class="actions">
        <a href="?filtro=todos" class="btn btn-sm <?= ($filtro === 'todos') ? 'btn-primary' : 'btn-secondary' ?>">Todos</a>
        <a href="?filtro=pendente" class="btn btn-sm <?= ($filtro === 'pendente') ? 'btn-primary' : 'btn-secondary' ?>">Pendentes</a>
        <a href="?filtro=aprovada" class="btn btn-sm <?= ($filtro === 'aprovada') ? 'btn-primary' : 'btn-secondary' ?>">Aprovados</a>
        <a href="?filtro=rejeitada" class="btn btn-sm <?= ($filtro === 'rejeitada') ? 'btn-primary' : 'btn-secondary' ?>">Rejeitados</a>
    </div>
</div>

<?php if (empty($matriculas)): ?>
    <div class="card"><p style="color:var(--text-muted);text-align:center;padding:2rem">Nenhum pedido encontrado.</p></div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Aluno</th>
                    <th>Curso</th>
                    <th>Ano Letivo</th>
                    <th>Estado</th>
                    <th>Submetido em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matriculas as $m): ?>
                <tr>
                    <td><?= $m['id'] ?></td>
                    <td>
                        <strong><?= e($m['aluno_nome']) ?></strong><br>
                        <small style="color:var(--text-muted)"><?= e($m['aluno_email'] ?? '') ?></small>
</xai:function_call >

<xai:function_call name="read_file">
                    </td>
                    <td><?= e($m['curso_nome']) ?></td>
                    <td><?= e($m['ano_letivo']) ?></td>
                    <td><span class="badge badge-<?= $m['estado'] ?>"><?= ucfirst($m['estado']) ?></span></td>
                    <td><?= formatDate($m['criado_em']) ?></td>
                    <td>
                        <?php if ($m['estado'] === 'pendente'): ?>
                            <a href="<?= APP_URL ?>/funcionario/matricula-decidir.php?id=<?= $m['id'] ?>" class="btn btn-primary btn-sm">Analisar</a>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/funcionario/matricula-decidir.php?id=<?= $m['id'] ?>" class="btn btn-secondary btn-sm">Ver</a>
                        <?php endif; ?>
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

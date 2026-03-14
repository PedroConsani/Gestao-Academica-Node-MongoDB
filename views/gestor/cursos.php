<?php // views/gestor/cursos.php
$pageTitle = 'Cursos';
ob_start(); ?>

<div class="page-header">
    <h1>🎓 Cursos</h1>
    <div class="actions">
        <a href="<?= APP_URL ?>/gestor/curso-novo.php" class="btn btn-success btn-sm">+ Novo Curso</a>
        <a href="<?= APP_URL ?>/gestor/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($cursos)): ?>
    <div class="card"><p style="color:var(--text-muted);text-align:center;padding:2rem">Nenhum curso criado.</p></div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Nome</th>
                    <th>Duração</th>
                    <th>Estado</th>
                    <th>Criado por</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $c): ?>
                <tr>
                    <td><strong><?= e($c['codigo']) ?></strong></td>
                    <td><?= e($c['nome']) ?></td>
                    <td><?= $c['duracao_anos'] ?> ano(s)</td>
                    <td>
                        <?php if ($c['ativo']): ?>
                            <span class="badge badge-aprovada">Ativo</span>
                        <?php else: ?>
                            <span class="badge badge-rejeitada">Inativo</span>
                        <?php endif; ?>
                    </td>
                    <td><?= e($c['criado_por_nome']) ?></td>
                    <td>
                        <div class="actions">
                            <a href="<?= APP_URL ?>/gestor/curso-editar.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="<?= APP_URL ?>/gestor/plano-estudos.php?curso_id=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">Plano</a>
                            <a href="<?= APP_URL ?>/gestor/curso-toggle.php?id=<?= $c['id'] ?>"
                               class="btn btn-sm <?= $c['ativo'] ? 'btn-danger' : 'btn-success' ?>"
                               onclick="return confirm('Confirma a alteração do estado?')">
                               <?= $c['ativo'] ? 'Desativar' : 'Ativar' ?>
                            </a>
                        </div>
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

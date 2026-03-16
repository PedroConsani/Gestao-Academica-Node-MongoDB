<?php // views/gestor/cursos.php
$pageTitle = 'Cursos';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Gestão Pedagógica</p>
        <h1>Cursos</h1>
    </div>
    <div class="actions">
        <a href="<?= APP_URL ?>/gestor/curso-novo.php" class="btn btn-primary btn-sm">+ Novo Curso</a>
        <a href="<?= APP_URL ?>/gestor/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($cursos)): ?>
    <div class="card" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;opacity:.3;margin-bottom:1rem;">🎓</div>
        <p style="color:var(--text-muted);">Nenhum curso criado.</p>
    </div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Código</th><th>Nome</th><th>Duração</th><th>Estado</th><th>Criado por</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php foreach ($cursos as $c): ?>
                <tr>
                    <td><strong style="color:var(--crimson);font-size:.82rem;letter-spacing:.04em;"><?= e($c['codigo']) ?></strong></td>
                    <td>
                        <strong style="font-size:.92rem;"><?= e($c['nome']) ?></strong>
                        <?php if ($c['descricao']): ?>
                            <br><small style="color:var(--text-muted);"><?= e(mb_substr($c['descricao'],0,60)) ?>...</small>
                        <?php endif; ?>
                    </td>
                    <td><?= $c['duracao_anos'] ?> ano(s)</td>
                    <td><span class="badge badge-<?= $c['ativo'] ? 'aprovada' : 'rejeitada' ?>"><?= $c['ativo'] ? 'Ativo' : 'Inativo' ?></span></td>
                    <td style="font-size:.82rem;color:var(--text-muted);"><?= e($c['criado_por_nome']) ?></td>
                    <td>
                        <div class="actions">
                            <a href="<?= APP_URL ?>/gestor/curso-editar.php?id=<?= $c['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="<?= APP_URL ?>/gestor/plano-estudos.php?curso_id=<?= $c['id'] ?>" class="btn btn-secondary btn-sm">Plano</a>
                            <a href="<?= APP_URL ?>/gestor/curso-toggle.php?id=<?= $c['id'] ?>"
                               class="btn btn-sm <?= $c['ativo'] ? 'btn-danger' : 'btn-success' ?>"
                               onclick="return confirm('Alterar estado?')">
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

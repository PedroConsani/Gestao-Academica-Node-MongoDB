<?php
// public/gestor/ucs.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_GESTOR);

$ucModel = new UCModel();
$ucs     = $ucModel->all();

// View inline simples para lista de UCs
$pageTitle = 'Unidades Curriculares';
ob_start(); ?>

<div class="page-header">
    <h1>📚 Unidades Curriculares</h1>
    <div class="actions">
        <a href="<?= APP_URL ?>/gestor/uc-nova.php" class="btn btn-success btn-sm">+ Nova UC</a>
        <a href="<?= APP_URL ?>/gestor/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($ucs)): ?>
    <div class="card"><p style="color:var(--text-muted);text-align:center;padding:2rem">Nenhuma UC criada.</p></div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Código</th><th>Nome</th><th>ECTS</th><th>Estado</th><th>Ações</th></tr>
            </thead>
            <tbody>
                <?php foreach ($ucs as $uc): ?>
                <tr>
                    <td><strong><?= e($uc['codigo']) ?></strong></td>
                    <td><?= e($uc['nome']) ?></td>
                    <td><?= $uc['creditos'] ?></td>
                    <td>
                        <?php if ($uc['ativo']): ?>
                            <span class="badge badge-aprovada">Ativa</span>
                        <?php else: ?>
                            <span class="badge badge-rejeitada">Inativa</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="actions">
                            <a href="<?= APP_URL ?>/gestor/uc-editar.php?id=<?= $uc['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="<?= APP_URL ?>/gestor/uc-toggle.php?id=<?= $uc['id'] ?>"
                               class="btn btn-sm <?= $uc['ativo'] ? 'btn-danger' : 'btn-success' ?>"
                               onclick="return confirm('Alterar estado desta UC?')">
                               <?= $uc['ativo'] ? 'Desativar' : 'Ativar' ?>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif;
$content = ob_get_clean();
include __DIR__ . '/../../views/layouts/main.php';

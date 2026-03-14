<?php // views/gestor/fichas.php
$pageTitle = 'Fichas de Alunos';
ob_start(); ?>

<div class="page-header">
    <h1>📋 Fichas de Alunos</h1>
    <a href="<?= APP_URL ?>/gestor/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="card" style="padding:.75rem 1rem;margin-bottom:1rem;">
    <div class="actions">
        <a href="?" class="btn btn-sm <?= ($filtro === 'todos') ? 'btn-primary' : 'btn-secondary' ?>">Todas</a>
        <a href="?filtro=submetida" class="btn btn-sm <?= ($filtro === 'submetida') ? 'btn-primary' : 'btn-secondary' ?>">Submetidas</a>
        <a href="?filtro=aprovada" class="btn btn-sm <?= ($filtro === 'aprovada') ? 'btn-primary' : 'btn-secondary' ?>">Aprovadas</a>
        <a href="?filtro=rejeitada" class="btn btn-sm <?= ($filtro === 'rejeitada') ? 'btn-primary' : 'btn-secondary' ?>">Rejeitadas</a>
    </div>
</div>

<?php if (empty($fichas)): ?>
    <div class="card"><p style="color:var(--text-muted);text-align:center;padding:2rem">Nenhuma ficha encontrada.</p></div>
<?php else: ?>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Foto</th>
                    <th>Aluno</th>
                    <th>Curso</th>
                    <th>Estado</th>
                    <th>Submetida em</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($fichas as $f): ?>
                <tr>
                    <td>
                        <?php if ($f['foto_path']): ?>
                            <img src="<?= e(\UploadHelper::fotoUrl($f['foto_path'])) ?>"
                                 style="width:40px;height:40px;border-radius:50%;object-fit:cover;">
                        <?php else: ?>
                            <span style="color:var(--text-muted)">—</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?= e($f['aluno_nome']) ?></strong><br>
                        <small style="color:var(--text-muted)"><?= e($f['aluno_email']) ?></small>
                    </td>
                    <td><?= $f['curso_nome'] ? e($f['curso_nome']) : '<span style="color:var(--text-muted)">—</span>' ?></td>
                    <td><span class="badge badge-<?= $f['estado'] ?>"><?= ucfirst($f['estado']) ?></span></td>
                    <td><?= formatDate($f['submetida_em']) ?></td>
                    <td>
                        <a href="<?= APP_URL ?>/gestor/ficha-validar.php?id=<?= $f['id'] ?>" class="btn btn-primary btn-sm">
                            <?= $f['estado'] === 'submetida' ? 'Validar' : 'Ver' ?>
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

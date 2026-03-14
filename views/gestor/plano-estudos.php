<?php // views/gestor/plano-estudos.php
$pageTitle = 'Plano de Estudos — ' . $curso['nome'];
ob_start(); ?>

<div class="page-header">
    <h1>📖 Plano de Estudos — <?= e($curso['nome']) ?></h1>
    <a href="<?= APP_URL ?>/gestor/cursos.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- UCs existentes no plano -->
<div class="card">
    <div class="card-title">UCs no Plano</div>
    <?php if (empty($plano)): ?>
        <p style="color:var(--text-muted);text-align:center;padding:1rem">Nenhuma UC associada.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Código</th><th>UC</th><th>Ano</th><th>Semestre</th><th>ECTS</th><th>Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($plano as $row): ?>
                    <tr>
                        <td><?= e($row['uc_codigo']) ?></td>
                        <td><?= e($row['uc_nome']) ?></td>
                        <td><?= $row['ano'] ?>º</td>
                        <td><?= $row['semestre'] ?>º Sem.</td>
                        <td><?= $row['creditos'] ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/gestor/plano-remover.php?id=<?= $row['id'] ?>&curso_id=<?= $curso['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Remover esta UC do plano?')">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Adicionar UC -->
<?php if ($ucsDisponiveis): ?>
<div class="card">
    <div class="card-title">Adicionar UC ao Plano</div>
    <form method="POST" action="<?= APP_URL ?>/gestor/plano-estudos.php?curso_id=<?= $curso['id'] ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Unidade Curricular *</label>
                <select name="uc_id" required>
                    <option value="">— Selecione —</option>
                    <?php foreach ($ucsDisponiveis as $uc): ?>
                        <option value="<?= $uc['id'] ?>"><?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Ano Curricular *</label>
                <select name="ano" required>
                    <?php for ($a = 1; $a <= $curso['duracao_anos']; $a++): ?>
                        <option value="<?= $a ?>"><?= $a ?>º Ano</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Semestre *</label>
                <select name="semestre" required>
                    <option value="1">1º Semestre</option>
                    <option value="2">2º Semestre</option>
                </select>
            </div>
        </div>
        <button type="submit" class="btn btn-success">Adicionar UC</button>
    </form>
</div>
<?php else: ?>
    <div class="alert alert-info">Todas as UCs disponíveis já estão associadas a este curso.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

<?php // views/aluno/matricula-nova.php
$pageTitle = 'Nova Matrícula';
ob_start(); ?>

<div class="page-header">
    <h1>📝 Novo Pedido de Matrícula</h1>
    <a href="<?= APP_URL ?>/aluno/matriculas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?><p><?= e($e) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="<?= APP_URL ?>/aluno/matricula-nova.php">
        <div class="form-group">
            <label>Curso *</label>
            <select name="curso_id" required>
                <option value="">— Selecione um curso —</option>
                <?php foreach ($cursos as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= ($_POST['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                        <?= e($c['nome']) ?> (<?= e($c['codigo']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Ano Letivo *</label>
            <input type="text" name="ano_letivo" placeholder="ex: 2024/2025" maxlength="9"
                   value="<?= e($_POST['ano_letivo'] ?? date('Y') . '/' . (date('Y')+1)) ?>" required>
        </div>
        <div class="form-group">
            <label>Observações (opcional)</label>
            <textarea name="observacoes" rows="3" placeholder="Informação adicional..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submeter Pedido</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

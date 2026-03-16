<?php // views/gestor/uc-form.php
$pageTitle = isset($uc) ? 'Editar UC' : 'Nova UC';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Gestão Pedagógica</p>
        <h1><?= isset($uc) ? 'Editar Unidade Curricular' : 'Nova Unidade Curricular' ?></h1>
    </div>
    <a href="<?= APP_URL ?>/gestor/ucs.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="<?= APP_URL ?>/gestor/<?= isset($uc) ? 'uc-editar.php?id=' . $uc['id'] : 'uc-nova.php' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nome da UC *</label>
                <input type="text" name="nome" maxlength="200" required
                       value="<?= e($uc['nome'] ?? $_POST['nome'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" maxlength="20" required
                       style="text-transform:uppercase"
                       value="<?= e($uc['codigo'] ?? $_POST['codigo'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="3"><?= e($uc['descricao'] ?? $_POST['descricao'] ?? '') ?></textarea>
        </div>
        <div class="form-group" style="max-width:200px;">
            <label>Créditos ECTS *</label>
            <input type="number" name="creditos" min="0.5" max="30" step="0.5" required
                   value="<?= e($uc['creditos'] ?? $_POST['creditos'] ?? '6') ?>">
        </div>
        <button type="submit" class="btn btn-primary">💾 Guardar</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

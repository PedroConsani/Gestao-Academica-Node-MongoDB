<?php // views/gestor/curso-form.php
$pageTitle = isset($curso) ? 'Editar Curso' : 'Novo Curso';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Gestão Pedagógica</p>
        <h1><?= isset($curso) ? 'Editar Curso' : 'Novo Curso' ?></h1>
    </div>
    <a href="<?= APP_URL ?>/gestor/cursos.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="<?= APP_URL ?>/gestor/<?= isset($curso) ? 'curso-editar.php?id=' . $curso['id'] : 'curso-novo.php' ?>">
        <div class="form-row">
            <div class="form-group">
                <label>Nome do Curso *</label>
                <input type="text" name="nome" maxlength="200" required
                       value="<?= e($curso['nome'] ?? $_POST['nome'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Código *</label>
                <input type="text" name="codigo" maxlength="20" required
                       style="text-transform:uppercase"
                       value="<?= e($curso['codigo'] ?? $_POST['codigo'] ?? '') ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Descrição</label>
            <textarea name="descricao" rows="3"><?= e($curso['descricao'] ?? $_POST['descricao'] ?? '') ?></textarea>
        </div>
        <div class="form-group" style="max-width:200px;">
            <label>Duração (anos) *</label>
            <select name="duracao_anos" required>
                <?php for ($a = 1; $a <= ANOS_MAX; $a++): ?>
                    <option value="<?= $a ?>" <?= ($curso['duracao_anos'] ?? $_POST['duracao_anos'] ?? 3) == $a ? 'selected' : '' ?>>
                        <?= $a ?> ano(s)
                    </option>
                <?php endfor; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">💾 Guardar</button>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

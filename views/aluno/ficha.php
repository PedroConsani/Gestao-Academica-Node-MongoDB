<?php // views/aluno/ficha.php
$pageTitle = 'Ficha de Aluno';
ob_start(); ?>

<div class="page-header">
    <h1>📋 Ficha de Aluno</h1>
    <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:1.2rem;">
            <?php foreach ($errors as $err): ?>
                <li><?= e($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php $readonly = isset($ficha) && in_array($ficha['estado'] ?? '', ['submetida', 'aprovada']); ?>
<?php if ($readonly): ?>
    <div class="alert alert-info">A ficha está em estado <strong><?= ucfirst($ficha['estado']) ?></strong> e não pode ser editada.</div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/aluno/ficha.php" enctype="multipart/form-data">
    <div class="card">
        <div class="card-title">Dados Pessoais</div>
        <div class="form-row">
            <div class="form-group">
                <label>Data de Nascimento *</label>
                <input type="date" name="data_nascimento" value="<?= e($ficha['data_nascimento'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Nacionalidade *</label>
                <input type="text" name="nacionalidade" value="<?= e($ficha['nacionalidade'] ?? 'Portuguesa') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>NIF *</label>
                <input type="text" name="nif" maxlength="9" value="<?= e($ficha['nif'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Cartão de Cidadão *</label>
                <input type="text" name="cc" maxlength="20" value="<?= e($ficha['cc'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
        </div>
        <div class="form-group">
            <label>Telefone *</label>
            <input type="tel" name="telefone" maxlength="20" value="<?= e($ficha['telefone'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Morada</div>
        <div class="form-group">
            <label>Morada *</label>
            <input type="text" name="morada" value="<?= e($ficha['morada'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Código Postal *</label>
                <input type="text" name="codigo_postal" placeholder="0000-000" maxlength="8" value="<?= e($ficha['codigo_postal'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Localidade *</label>
                <input type="text" name="localidade" value="<?= e($ficha['localidade'] ?? '') ?>" <?= $readonly ? 'disabled' : '' ?>>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Curso e Fotografia</div>
        <div class="form-row">
            <div class="form-group">
                <label>Curso Pretendido *</label>
                <select name="curso_id" <?= $readonly ? 'disabled' : '' ?>>
                    <option value="">— Selecione um curso —</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($ficha['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['nome']) ?> (<?= e($c['codigo']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fotografia (JPG/PNG, máx. 2MB)</label>
                <?php if (!empty($ficha['foto_path'])): ?>
                    <img src="<?= e(\UploadHelper::fotoUrl($ficha['foto_path'])) ?>" class="foto-preview" style="display:block;margin-bottom:.5rem;">
                <?php endif; ?>
                <?php if (!$readonly): ?>
                    <input type="file" name="foto" accept=".jpg,.jpeg,.png">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!$readonly): ?>
        <div class="actions">
            <button type="submit" name="acao" value="guardar" class="btn btn-primary">💾 Guardar Rascunho</button>
            <button type="submit" name="acao" value="submeter" class="btn btn-success"
                onclick="return confirm('Confirma a submissão da ficha? Após submeter não poderá editar.')">
                ✅ Guardar e Submeter
            </button>
        </div>
    <?php endif; ?>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

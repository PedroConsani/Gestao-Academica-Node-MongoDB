<?php // views/funcionario/pauta-nova.php
$pageTitle = 'Nova Pauta';
ob_start(); ?>

<div class="page-header">
    <h1>📝 Criar Nova Pauta</h1>
    <a href="<?= APP_URL ?>/funcionario/pautas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <form method="POST" action="<?= APP_URL ?>/funcionario/pauta-nova.php">
        <div class="form-row">
            <div class="form-group">
                <label>Curso *</label>
                <select name="curso_id" required id="curso_id">
                    <option value="">— Selecione —</option>
                    <?php foreach ($cursos as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($_POST['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Unidade Curricular *</label>
                <select name="uc_id" required id="uc_id">
                    <option value="">— Selecione um curso primeiro —</option>
                    <?php foreach ($ucs as $uc): ?>
                        <option value="<?= $uc['id'] ?>" <?= ($_POST['uc_id'] ?? '') == $uc['id'] ? 'selected' : '' ?>>
                            <?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Ano Letivo *</label>
                <input type="text" name="ano_letivo" placeholder="2024/2025" maxlength="9"
                       value="<?= e($_POST['ano_letivo'] ?? date('Y') . '/' . (date('Y')+1)) ?>" required>
            </div>
            <div class="form-group">
                <label>Época *</label>
                <select name="epoca" required>
                    <?php foreach (EPOCAS as $ep): ?>
                        <option value="<?= $ep ?>" <?= ($_POST['epoca'] ?? 'Normal') === $ep ? 'selected' : '' ?>><?= $ep ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="alert alert-info">
            Os alunos com matrícula aprovada no curso/ano letivo selecionado serão automaticamente adicionados à pauta.
        </div>
        <button type="submit" class="btn btn-success">Criar Pauta</button>
    </form>
</div>

<script>
// Filtrar UCs pelo curso selecionado via AJAX simples
const ucData = <?= json_encode($ucsPorCurso) ?>;
document.getElementById('curso_id').addEventListener('change', function() {
    const sel = document.getElementById('uc_id');
    const ucs = ucData[this.value] || [];
    sel.innerHTML = ucs.length
        ? ucs.map(u => `<option value="${u.id}">${u.codigo} — ${u.nome}</option>`).join('')
        : '<option value="">Sem UCs neste curso</option>';
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

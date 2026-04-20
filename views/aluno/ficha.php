<?php // views/aluno/ficha.php
$pageTitle = 'Ficha de Aluno';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Portal Académico do Aluno</p>
        <h1>Ficha de Aluno</h1>
    </div>
    <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul style="margin:0;padding-left:1.2rem;">
            <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php $readonly = isset($ficha) && in_array($ficha['estado'] ?? '', ['submetida', 'aprovada']); ?>
<?php if ($readonly): ?>
    <div class="alert alert-info">
        A ficha está em estado <strong><?= ucfirst($ficha['estado']) ?></strong> e não pode ser editada.
    </div>
<?php endif; ?>

<?php if (!$readonly): ?>
<div style="margin-bottom:1.25rem;">
    <button type="button" onclick="preencherAleatorio()" class="btn btn-secondary btn-sm">
        🎲 Preencher com dados aleatórios
    </button>
</div>
<?php endif; ?>

<form method="POST" action="<?= APP_URL ?>/aluno/ficha.php" enctype="multipart/form-data">

    <div class="card">
        <div class="card-title">Dados Pessoais</div>
        <div class="form-row">
            <div class="form-group">
                <label>Data de Nascimento *</label>
                <input type="date" name="data_nascimento"
                       value="<?= e($ficha['data_nascimento'] ?? '') ?>"
                       <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Nacionalidade *</label>
                <input type="text" name="nacionalidade"
                       value="<?= e($ficha['nacionalidade'] ?? 'Portuguesa') ?>"
                       <?= $readonly ? 'disabled' : '' ?>>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>NIF *</label>
                <input type="text" name="nif" maxlength="9"
                       value="<?= e($ficha['nif'] ?? '') ?>"
                       placeholder="123456789"
                       <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Cartão de Cidadão *</label>
                <input type="text" name="cc" maxlength="20"
                       value="<?= e($ficha['cc'] ?? '') ?>"
                       <?= $readonly ? 'disabled' : '' ?>>
            </div>
        </div>
        <div class="form-group">
            <label>Telefone *</label>
            <input type="tel" name="telefone" maxlength="20"
                   value="<?= e($ficha['telefone'] ?? '') ?>"
                   placeholder="9XX XXX XXX"
                   <?= $readonly ? 'disabled' : '' ?>>
        </div>
    </div>

    <div class="card">
        <div class="card-title">Morada</div>
        <div class="form-group">
            <label>Morada *</label>
            <input type="text" name="morada"
                   value="<?= e($ficha['morada'] ?? '') ?>"
                   placeholder="Rua, nº, andar"
                   <?= $readonly ? 'disabled' : '' ?>>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Código Postal *</label>
                <input type="text" name="codigo_postal" placeholder="0000-000" maxlength="8"
                       value="<?= e($ficha['codigo_postal'] ?? '') ?>"
                       <?= $readonly ? 'disabled' : '' ?>>
            </div>
            <div class="form-group">
                <label>Localidade *</label>
                <input type="text" name="localidade"
                       value="<?= e($ficha['localidade'] ?? '') ?>"
                       <?= $readonly ? 'disabled' : '' ?>>
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
                        <option value="<?= $c['id'] ?>"
                            <?= ($ficha['curso_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
                            <?= e($c['nome']) ?> (<?= e($c['codigo']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Fotografia <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;">(JPG/PNG, máx. 2MB)</span></label>
                <?php if (!empty($ficha['foto_path'])): ?>
                    <div style="margin-bottom:.75rem;">
                        <img src="<?= e(\UploadHelper::fotoUrl($ficha['foto_path'])) ?>"
                             class="foto-preview">
                    </div>
                <?php endif; ?>
                <?php if (!$readonly): ?>
                    <input type="file" name="foto" accept=".jpg,.jpeg,.png">
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if (!$readonly): ?>
        <div class="actions" style="margin-bottom:2rem;">
            <button type="submit" name="acao" value="guardar" class="btn btn-secondary">
                💾 Guardar Rascunho
            </button>
            <button type="submit" name="acao" value="submeter" class="btn btn-primary"
                onclick="return confirm('Confirma a submissão? Após submeter não poderá editar.')">
                📤 Guardar e Submeter
            </button>
        </div>
    <?php endif; ?>
</form>

<?php if (!$readonly): ?>
<script>
function preencherAleatorio() {
    const nomes     = ['Silva','Santos','Ferreira','Costa','Oliveira','Rodrigues','Martins','Sousa','Pereira','Carvalho'];
    const ruas      = ['Rua da Liberdade','Avenida dos Aliados','Rua de Santo António','Travessa do Carmo','Rua do Ouro','Avenida da República','Rua das Flores','Rua do Almada'];
    const localidades = ['Lisboa','Porto','Braga','Coimbra','Aveiro','Faro','Setúbal','Viseu','Évora','Leiria'];
    const rand      = (arr) => arr[Math.floor(Math.random() * arr.length)];
    const randInt   = (min, max) => Math.floor(Math.random() * (max - min + 1)) + min;
    const padZero   = (n, len) => String(n).padStart(len, '0');

    // Data nascimento — entre 1985 e 2004
    const ano  = randInt(1985, 2004);
    const mes  = randInt(1, 12);
    const dia  = randInt(1, 28);
    document.querySelector('[name=data_nascimento]').value =
        `${ano}-${padZero(mes,2)}-${padZero(dia,2)}`;

    // Nacionalidade
    document.querySelector('[name=nacionalidade]').value = 'Portuguesa';

    // NIF — 9 dígitos começando por 1 ou 2
    const nifPrimeiro = rand(['1','2']);
    let nif = nifPrimeiro;
    for (let i = 0; i < 8; i++) nif += randInt(0, 9);
    document.querySelector('[name=nif]').value = nif;

    // CC — formato XXXXXXXXZZ (8 dígitos + 2 letras)
    let cc = '';
    for (let i = 0; i < 8; i++) cc += randInt(0, 9);
    cc += String.fromCharCode(randInt(65,90)) + String.fromCharCode(randInt(65,90));
    document.querySelector('[name=cc]').value = cc;

    // Telefone — começa por 9
    let tel = '9' + rand(['1','2','3','6']) + randInt(1000000, 9999999);
    document.querySelector('[name=telefone]').value = tel;

    // Morada
    const numPorta = randInt(1, 350);
    const andar    = rand(['', ' 1º Dto', ' 2º Esq', ' R/C', ' 3º Dto']);
    document.querySelector('[name=morada]').value = `${rand(ruas)}, ${numPorta}${andar}`;

    // Código postal
    const cp1 = padZero(randInt(1000, 9999), 4);
    const cp2 = padZero(randInt(100, 999), 3);
    document.querySelector('[name=codigo_postal]').value = `${cp1}-${cp2}`;

    // Localidade
    document.querySelector('[name=localidade]').value = rand(localidades);

    // Curso — selecionar o primeiro disponível se nenhum estiver selecionado
    const sel = document.querySelector('[name=curso_id]');
    if (sel && sel.value === '') {
        const opcoes = sel.querySelectorAll('option[value]:not([value=""])');
        if (opcoes.length > 0) {
            sel.value = opcoes[Math.floor(Math.random() * opcoes.length)].value;
        }
    }
}
</script>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
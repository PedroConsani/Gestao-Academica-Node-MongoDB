<?php // views/auth/register.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div style="background:var(--crimson);height:6px;"></div>

<div style="max-width:500px;margin:3.5rem auto;padding:0 1.5rem;">
    <div style="text-align:center;margin-bottom:2rem;">
        <div style="font-size:2.5rem;margin-bottom:.6rem;">🎓</div>
        <h1 style="font-family:'Playfair Display',Georgia,serif;font-size:1.7rem;font-weight:700;
                   color:var(--text);margin-bottom:.3rem;"><?= APP_NAME ?></h1>
        <p style="font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;color:var(--crimson);">
            Criar Conta de Aluno
        </p>
    </div>

    <div class="card" style="border-top:3px solid var(--crimson);">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <ul style="margin:0;padding-left:1.2rem;">
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/register.php" novalidate
              onsubmit="return validarFormulario()">

            <div class="form-group">
                <label for="nome">Nome Completo *</label>
                <input type="text" id="nome" name="nome" maxlength="150" required
                       value="<?= e($_POST['nome'] ?? '') ?>" placeholder="O seu nome completo">
                <span class="error" id="err-nome"></span>
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" maxlength="150" required
                       value="<?= e($_POST['email'] ?? '') ?>" placeholder="email@exemplo.pt">
                <span class="error" id="err-email"></span>
            </div>
            <div class="form-group">
                <label for="password">Password * <span style="font-weight:400;text-transform:none;letter-spacing:0;font-size:.75rem;">(mínimo 8 caracteres)</span></label>
                <input type="password" id="password" name="password" required>
                <span class="error" id="err-password"></span>
                <!-- Barra de força -->
                <div id="strength-wrap" style="display:none;margin-top:.5rem;">
                    <div style="height:4px;background:var(--border);border-radius:2px;overflow:hidden;">
                        <div id="strength-bar" style="height:100%;width:0;transition:width .3s,background .3s;border-radius:2px;"></div>
                    </div>
                    <span id="strength-label" style="font-size:.72rem;margin-top:.25rem;display:block;font-weight:600;"></span>
                </div>
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label for="password_confirm">Confirmar Password *</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
                <span class="error" id="err-password_confirm"></span>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem;">
                Criar Conta
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:.875rem;color:var(--text-muted);">
            Já tem conta?
            <a href="<?= APP_URL ?>/login.php" style="color:var(--crimson);font-weight:600;text-decoration:none;">
                Iniciar Sessão
            </a>
        </p>
    </div>
</div>

<script>
const pwdInput = document.getElementById('password');
const bar = document.getElementById('strength-bar');
const label = document.getElementById('strength-label');
const wrap = document.getElementById('strength-wrap');
pwdInput.addEventListener('input', function() {
    const v = this.value;
    wrap.style.display = v ? 'block' : 'none';
    let s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const lvl = [
        {p:'25%',c:'#dc2626',t:'Fraca'},
        {p:'50%',c:'#d97706',t:'Razoável'},
        {p:'75%',c:'#ca8a04',t:'Boa'},
        {p:'100%',c:'#16a34a',t:'Forte'},
    ][s-1] || {p:'25%',c:'#dc2626',t:'Fraca'};
    bar.style.width = lvl.p; bar.style.background = lvl.c;
    label.textContent = lvl.t; label.style.color = lvl.c;
});
function validarFormulario() {
    let ok = true;
    [['nome','err-nome','O nome é obrigatório.'],
     ['email','err-email','O email é obrigatório.'],
     ['password','err-password','A password é obrigatória.'],
     ['password_confirm','err-password_confirm','A confirmação é obrigatória.']
    ].forEach(([id,eid,msg]) => {
        const el = document.getElementById(id);
        const er = document.getElementById(eid);
        er.textContent = '';
        if (!el.value.trim()) { er.textContent = msg; ok = false; }
    });
    const pw = document.getElementById('password');
    const ep = document.getElementById('err-password');
    if (pw.value && pw.value.length < 8) { ep.textContent = 'Mínimo 8 caracteres.'; ok = false; }
    const pc = document.getElementById('password_confirm');
    const ec = document.getElementById('err-password_confirm');
    if (pw.value && pc.value && pw.value !== pc.value) { ec.textContent = 'As passwords não coincidem.'; ok = false; }
    return ok;
}
</script>
</body>
</html>

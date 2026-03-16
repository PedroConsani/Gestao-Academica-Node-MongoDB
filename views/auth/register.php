<?php // views/auth/register.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Conta — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div class="container">
    <div class="login-wrap">
        <div class="card" style="margin-top:3rem;">
            <div class="card-title">🎓 Criar Conta de Aluno</div>

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
                           value="<?= e($_POST['nome'] ?? '') ?>"
                           placeholder="O seu nome completo">
                    <span class="error" id="err-nome"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" maxlength="150" required
                           value="<?= e($_POST['email'] ?? '') ?>"
                           placeholder="exemplo@email.com">
                    <span class="error" id="err-email"></span>
                </div>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Mínimo 8 caracteres">
                    <span class="error" id="err-password"></span>
                </div>

                <div class="form-group">
                    <label for="password_confirm">Confirmar Password *</label>
                    <input type="password" id="password_confirm" name="password_confirm" required
                           placeholder="Repita a password">
                    <span class="error" id="err-password_confirm"></span>
                </div>

                <!-- Indicador de força da password -->
                <div id="strength-wrap" style="display:none;margin-bottom:1rem;">
                    <div style="font-size:.8rem;color:var(--text-muted);margin-bottom:.3rem;">Força da password:</div>
                    <div style="height:6px;border-radius:3px;background:var(--border);overflow:hidden;">
                        <div id="strength-bar" style="height:100%;width:0;transition:width .3s,background .3s;border-radius:3px;"></div>
                    </div>
                    <div id="strength-label" style="font-size:.75rem;margin-top:.25rem;"></div>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Criar Conta
                </button>
            </form>

            <p style="text-align:center;margin-top:1.25rem;font-size:.875rem;color:var(--text-muted);">
                Já tem conta?
                <a href="<?= APP_URL ?>/login.php" style="color:var(--primary);font-weight:500;">Iniciar Sessão</a>
            </p>
        </div>
    </div>
</div>

<script>
// Força da password
const pwdInput = document.getElementById('password');
const bar      = document.getElementById('strength-bar');
const label    = document.getElementById('strength-label');
const wrap     = document.getElementById('strength-wrap');

pwdInput.addEventListener('input', function () {
    const val = this.value;
    wrap.style.display = val ? 'block' : 'none';

    let score = 0;
    if (val.length >= 8)               score++;
    if (/[A-Z]/.test(val))             score++;
    if (/[0-9]/.test(val))             score++;
    if (/[^A-Za-z0-9]/.test(val))      score++;

    const levels = [
        { pct: '25%', color: '#ef4444', text: 'Fraca' },
        { pct: '50%', color: '#f97316', text: 'Razoável' },
        { pct: '75%', color: '#eab308', text: 'Boa' },
        { pct: '100%', color: '#22c55e', text: 'Forte' },
    ];
    const lvl = levels[score - 1] || levels[0];
    bar.style.width      = lvl.pct;
    bar.style.background = lvl.color;
    label.textContent    = lvl.text;
    label.style.color    = lvl.color;
});

// Validação client-side
function validarFormulario() {
    let ok = true;

    const campos = [
        { id: 'nome',             errId: 'err-nome',             msg: 'O nome é obrigatório.' },
        { id: 'email',            errId: 'err-email',            msg: 'O email é obrigatório.' },
        { id: 'password',         errId: 'err-password',         msg: 'A password é obrigatória.' },
        { id: 'password_confirm', errId: 'err-password_confirm', msg: 'A confirmação é obrigatória.' },
    ];

    campos.forEach(c => {
        const el  = document.getElementById(c.id);
        const err = document.getElementById(c.errId);
        err.textContent = '';
        if (!el.value.trim()) {
            err.textContent = c.msg;
            ok = false;
        }
    });

    // Validar email
    const email    = document.getElementById('email');
    const errEmail = document.getElementById('err-email');
    if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        errEmail.textContent = 'Introduza um email válido.';
        ok = false;
    }

    // Validar comprimento da password
    const pwd    = document.getElementById('password');
    const errPwd = document.getElementById('err-password');
    if (pwd.value && pwd.value.length < 8) {
        errPwd.textContent = 'A password deve ter pelo menos 8 caracteres.';
        ok = false;
    }

    // Validar confirmação
    const confirm    = document.getElementById('password_confirm');
    const errConfirm = document.getElementById('err-password_confirm');
    if (pwd.value && confirm.value && pwd.value !== confirm.value) {
        errConfirm.textContent = 'As passwords não coincidem.';
        ok = false;
    }

    return ok;
}
</script>
</body>
</html>

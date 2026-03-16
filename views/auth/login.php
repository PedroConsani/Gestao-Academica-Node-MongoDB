<?php // views/auth/login.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div class="container">
    <div class="login-wrap">
        <div class="card" style="margin-top:3rem;">
            <div class="card-title">🎓 <?= APP_NAME ?></div>

            <?php if ($expired): ?>
                <div class="alert alert-warning">A sua sessão expirou. Por favor inicie sessão novamente.</div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/login.php" novalidate>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autocomplete="email"
                           placeholder="utilizador@academia.pt">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required autocomplete="current-password">
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    Iniciar Sessão
                </button>
            </form>

            <p style="text-align:center;margin-top:1.25rem;font-size:.875rem;color:var(--text-muted);">
                Ainda não tem conta?
                <a href="<?= APP_URL ?>/register.php" style="color:var(--primary);font-weight:500;">Criar conta de aluno</a>
            </p>
        </div>
    </div>
</div>
</body>
</html>

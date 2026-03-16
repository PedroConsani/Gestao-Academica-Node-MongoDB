<?php // views/auth/login.php ?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sessão — <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Source+Sans+3:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>

<!-- Top bar -->
<div style="background:var(--crimson);height:6px;"></div>

<div style="max-width:460px;margin:4rem auto;padding:0 1.5rem;">

    <!-- Header institucional -->
    <div style="text-align:center;margin-bottom:2.5rem;">
        <div style="font-size:3rem;margin-bottom:.75rem;">🎓</div>
        <h1 style="font-family:'Playfair Display',Georgia,serif;font-size:1.9rem;font-weight:700;
                   color:var(--text);letter-spacing:-.01em;margin-bottom:.4rem;">
            <?= APP_NAME ?>
        </h1>
        <p style="font-size:.82rem;font-weight:600;text-transform:uppercase;letter-spacing:.1em;
                  color:var(--crimson);">Portal Académico</p>
    </div>

    <div class="card" style="border-top:3px solid var(--crimson);">
        <div class="card-title" style="justify-content:center;border-bottom:none;
             font-size:1.1rem;margin-bottom:1.5rem;padding-bottom:0;">
            Iniciar Sessão
        </div>

        <?php if ($expired): ?>
            <div class="alert alert-warning">A sua sessão expirou. Por favor inicie sessão novamente.</div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/login.php" novalidate>
            <div class="form-group">
                <label for="email">Endereço de Email</label>
                <input type="email" id="email" name="email" required
                       autocomplete="email" placeholder="utilizador@academia.pt">
            </div>
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required
                       autocomplete="current-password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.75rem;">
                Entrar
            </button>
        </form>

        <p style="text-align:center;margin-top:1.5rem;font-size:.875rem;color:var(--text-muted);">
            Ainda não tem conta?
            <a href="<?= APP_URL ?>/register.php"
               style="color:var(--crimson);font-weight:600;text-decoration:none;">
               Criar conta de aluno
            </a>
        </p>
    </div>

    <p style="text-align:center;margin-top:1.5rem;font-size:.75rem;color:var(--text-dim);">
        &copy; <?= date('Y') ?> <?= APP_NAME ?> &nbsp;·&nbsp; Serviços Académicos
    </p>
</div>
</body>
</html>

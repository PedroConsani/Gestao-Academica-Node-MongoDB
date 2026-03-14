<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Acesso Negado — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<div class="container" style="text-align:center;margin-top:5rem;">
    <div class="card" style="max-width:480px;margin:0 auto;">
        <div style="font-size:4rem;margin-bottom:1rem;">🚫</div>
        <h1 style="font-size:1.5rem;margin-bottom:.75rem;">Acesso Negado</h1>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">
            Não tem permissões para aceder a esta página.
        </p>
        <a href="<?= APP_URL ?>/login.php" class="btn btn-primary">Voltar ao Login</a>
    </div>
</div>
</body>
</html>

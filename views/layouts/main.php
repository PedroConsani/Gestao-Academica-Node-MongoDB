<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? APP_NAME) ?> — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= APP_URL ?>/css/style.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">🎓 <?= APP_NAME ?></div>
    <?php if (isLoggedIn()): $u = currentUser(); ?>
    <div class="navbar-user">
        <span class="badge badge-<?= $u['role'] ?>"><?= ucfirst($u['role']) ?></span>
        <span><?= e($u['nome']) ?></span>
        <a href="<?= APP_URL ?>/logout.php" class="btn btn-sm btn-outline">Sair</a>
    </div>
    <?php endif; ?>
</nav>

<div class="container">
    <?php if ($flash = getFlash('success')): ?>
        <div class="alert alert-success"><?= e($flash) ?></div>
    <?php endif; ?>
    <?php if ($flash = getFlash('error')): ?>
        <div class="alert alert-error"><?= e($flash) ?></div>
    <?php endif; ?>

    <?= $content ?? '' ?>
</div>

<footer class="footer">
    <p>&copy; <?= date('Y') ?> <?= APP_NAME ?></p>
</footer>
</body>
</html>

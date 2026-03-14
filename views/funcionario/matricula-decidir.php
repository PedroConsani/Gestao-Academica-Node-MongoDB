<?php // views/funcionario/matricula-decidir.php
$pageTitle = 'Analisar Matrícula';
ob_start(); ?>

<div class="page-header">
    <h1>🔍 Analisar Pedido #<?= $matricula['id'] ?></h1>
    <a href="<?= APP_URL ?>/funcionario/matriculas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Dados do Pedido</div>
        <p><strong>Aluno:</strong> <?= e($matricula['aluno_nome']) ?></p>
        <p><strong>Email:</strong> <?= e($matricula['aluno_email']) ?></p>
        <p><strong>Curso:</strong> <?= e($matricula['curso_nome']) ?></p>
        <p><strong>Ano Letivo:</strong> <?= e($matricula['ano_letivo']) ?></p>
        <p><strong>Estado:</strong> <span class="badge badge-<?= $matricula['estado'] ?>"><?= ucfirst($matricula['estado']) ?></span></p>
        <p><strong>Submetido:</strong> <?= formatDate($matricula['criado_em']) ?></p>
        <?php if ($matricula['observacoes_aluno']): ?>
            <p><strong>Obs. Aluno:</strong> <?= e($matricula['observacoes_aluno']) ?></p>
        <?php endif; ?>
    </div>

    <?php if ($matricula['estado'] !== 'pendente'): ?>
    <div class="card">
        <div class="card-title">Decisão Registada</div>
        <p><strong>Decisão:</strong> <span class="badge badge-<?= $matricula['estado'] ?>"><?= ucfirst($matricula['estado']) ?></span></p>
        <p><strong>Por:</strong> <?= e($matricula['decidido_por_nome']) ?></p>
        <p><strong>Em:</strong> <?= formatDate($matricula['decidido_em']) ?></p>
        <?php if ($matricula['observacoes_func']): ?>
            <p><strong>Observações:</strong> <?= e($matricula['observacoes_func']) ?></p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>

<?php if ($matricula['estado'] === 'pendente'): ?>
<div class="card">
    <div class="card-title">Registar Decisão</div>
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
    <?php endif; ?>
    <form method="POST" action="<?= APP_URL ?>/funcionario/matricula-decidir.php?id=<?= $matricula['id'] ?>">
        <div class="form-group">
            <label>Observações (opcional)</label>
            <textarea name="observacoes" rows="3" placeholder="Justificação da decisão..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
        </div>
        <div class="actions">
            <button type="submit" name="decisao" value="aprovada" class="btn btn-success"
                onclick="return confirm('Confirma a aprovação deste pedido?')">✅ Aprovar</button>
            <button type="submit" name="decisao" value="rejeitada" class="btn btn-danger"
                onclick="return confirm('Confirma a rejeição deste pedido?')">❌ Rejeitar</button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

<?php // views/aluno/matriculas.php
$pageTitle = 'As Minhas Matrículas';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Portal Académico do Aluno</p>
        <h1>Matrículas / Inscrições</h1>
    </div>
    <div class="actions">
        <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary btn-sm">+ Nova Matrícula</a>
        <a href="<?= APP_URL ?>/aluno/dashboard.php" class="btn btn-secondary btn-sm">← Voltar</a>
    </div>
</div>

<?php if (empty($matriculas)): ?>
    <div class="card" style="text-align:center;padding:3rem;">
        <div style="font-size:3rem;margin-bottom:1rem;opacity:.3;">🎓</div>
        <p style="color:var(--text-muted);margin-bottom:1.5rem;">Ainda não submeteu nenhum pedido de matrícula.</p>
        <a href="<?= APP_URL ?>/aluno/matricula-nova.php" class="btn btn-primary">+ Nova Matrícula</a>
    </div>
<?php else: ?>

    <?php foreach ($matriculas as $m):
        $numUcs = (int) ($ucsPorCurso[$m['curso_id']] ?? 0);
    ?>
    <div class="card" style="margin-bottom:1.5rem;">

        <!-- Cabeçalho da matrícula -->
        <div style="display:flex;justify-content:space-between;align-items:flex-start;
                    padding-bottom:1rem;margin-bottom:1rem;border-bottom:1px solid var(--border);gap:1rem;">
            <div>
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;
                            letter-spacing:.1em;color:var(--crimson);margin-bottom:.3rem;">
                    Matrícula #<?= $m['id'] ?>
                </div>
                <h3 style="font-family:'Playfair Display',Georgia,serif;font-size:1.15rem;
                           font-weight:700;color:var(--text);margin-bottom:.3rem;">
                    <?= e($m['curso_nome']) ?>
                </h3>
                <div style="display:flex;gap:1.25rem;align-items:center;flex-wrap:wrap;">
                    <span style="font-size:.82rem;color:var(--text-muted);">
                        📅 Ano letivo: <strong><?= e($m['ano_letivo']) ?></strong>
                    </span>
                    <span style="font-size:.82rem;color:var(--text-muted);">
                        📚 <?= $numUcs ?> UC<?= $numUcs !== 1 ? 's' : '' ?> no plano
                    </span>
                    <?php if ($m['criado_em']): ?>
                    <span style="font-size:.82rem;color:var(--text-muted);">
                        🕐 Submetida em <?= formatDate($m['criado_em']) ?>
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <div style="text-align:right;flex-shrink:0;">
                <span class="badge badge-<?= $m['estado'] ?>" style="font-size:.75rem;">
                    <?= ucfirst($m['estado']) ?>
                </span>
                <?php if ($m['decidido_em']): ?>
                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:.4rem;">
                        por <?= e($m['decidido_por_nome'] ?? '—') ?><br>
                        em <?= formatDate($m['decidido_em']) ?>
                    </div>
                <?php endif; ?>
                <?php if ($m['estado'] === 'aprovada'): ?>
                    <div style="margin-top:.75rem;">
                        <a href="<?= APP_URL ?>/aluno/notas-curso.php?id=<?= $m['curso_id'] ?>"
                           class="btn btn-primary btn-sm">📊 Ver Notas</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Observações -->
        <?php if ($m['observacoes_aluno']): ?>
            <div class="alert alert-info" style="margin-bottom:1rem;font-size:.875rem;">
                <strong>A sua nota:</strong> <?= e($m['observacoes_aluno']) ?>
            </div>
        <?php endif; ?>
        <?php if ($m['observacoes_func']): ?>
            <div class="alert alert-<?= $m['estado'] === 'aprovada' ? 'success' : 'error' ?>" style="margin-bottom:1rem;font-size:.875rem;">
                <strong>Decisão dos Serviços Académicos:</strong> <?= e($m['observacoes_func']) ?>
            </div>
        <?php endif; ?>

    </div>
    <?php endforeach; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
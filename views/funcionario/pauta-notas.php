<?php // views/funcionario/pauta-notas.php
$pageTitle = 'Notas — ' . $pauta['uc_nome'];
ob_start(); ?>

<div class="page-header">
    <h1>📊 Pauta: <?= e($pauta['uc_codigo']) ?> — <?= e($pauta['uc_nome']) ?></h1>
    <a href="<?= APP_URL ?>/funcionario/pautas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="card" style="margin-bottom:1rem;">
    <div style="display:flex;gap:2rem;flex-wrap:wrap;">
        <div><strong>Curso:</strong> <?= e($pauta['curso_nome']) ?></div>
        <div><strong>Ano Letivo:</strong> <?= e($pauta['ano_letivo']) ?></div>
        <div><strong>Época:</strong> <?= e($pauta['epoca']) ?></div>
        <div><strong>Estado:</strong>
            <?php if ($pauta['fechada']): ?>
                <span class="badge badge-aprovada">Fechada</span>
            <?php else: ?>
                <span class="badge badge-submetida">Aberta</span>
            <?php endif; ?>
        </div>
        <div><strong>Criada por:</strong> <?= e($pauta['criada_por_nome']) ?> em <?= formatDate($pauta['criada_em']) ?></div>
    </div>
</div>

<?php if (empty($notas)): ?>
    <div class="alert alert-warning">Nenhum aluno elegível encontrado nesta pauta.</div>
<?php else: ?>

<?php if (!$pauta['fechada']): ?>
<form method="POST" action="<?= APP_URL ?>/funcionario/pauta-notas.php?id=<?= $pauta['id'] ?>">
<?php endif; ?>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Aluno</th>
                    <th>Email</th>
                    <th>Nota Final (0–20)</th>
                    <th>Última edição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notas as $i => $n): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= e($n['aluno_nome']) ?></td>
                    <td><?= e($n['aluno_email']) ?></td>
                    <td>
                        <?php if (!$pauta['fechada']): ?>
                            <input type="number" name="notas[<?= $n['aluno_id'] ?>]"
                                   min="0" max="20" step="0.1"
                                   value="<?= $n['nota_final'] !== null ? e($n['nota_final']) : '' ?>"
                                   style="width:80px;padding:.3rem .5rem;border:1px solid var(--border);border-radius:4px;">
                        <?php else: ?>
                            <?= $n['nota_final'] !== null ? e($n['nota_final']) : '—' ?>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($n['editado_em']): ?>
                            <?= formatDate($n['editado_em']) ?>
                            <small style="color:var(--text-muted)"> por <?= e($n['editado_por_nome'] ?? '') ?></small>
                        <?php else: ?>
                            <span style="color:var(--text-muted)">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (!$pauta['fechada']): ?>
    <div class="actions" style="margin-top:1rem;">
        <button type="submit" name="acao" value="guardar" class="btn btn-primary">💾 Guardar Notas</button>
        <button type="submit" name="acao" value="fechar" class="btn btn-danger"
            onclick="return confirm('Fechar a pauta impede edições futuras. Confirma?')">🔒 Fechar Pauta</button>
    </div>
    <?php endif; ?>
</div>

<?php if (!$pauta['fechada']): ?>
</form>
<?php endif; ?>

<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

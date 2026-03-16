<?php // views/funcionario/pauta-notas.php
$pageTitle = 'Notas — ' . $pauta['uc_nome'];
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Serviços Académicos · Pautas</p>
        <h1><?= e($pauta['uc_codigo']) ?> — <?= e($pauta['uc_nome']) ?></h1>
    </div>
    <a href="<?= APP_URL ?>/funcionario/pautas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<!-- Info pauta -->
<div class="card" style="margin-bottom:1.5rem;">
    <div style="display:flex;gap:2.5rem;flex-wrap:wrap;font-size:.875rem;">
        <?php foreach ([
            'Curso'      => $pauta['curso_nome'],
            'Ano Letivo' => $pauta['ano_letivo'],
            'Época'      => $pauta['epoca'],
            'Criada por' => $pauta['criada_por_nome'] . ' em ' . formatDate($pauta['criada_em']),
        ] as $k => $v): ?>
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;
                         color:var(--text-muted);display:block;margin-bottom:.2rem;"><?= $k ?></span>
            <span style="font-weight:600;"><?= e($v) ?></span>
        </div>
        <?php endforeach; ?>
        <div>
            <span style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;
                         color:var(--text-muted);display:block;margin-bottom:.2rem;">Estado</span>
            <span class="badge badge-<?= $pauta['fechada'] ? 'aprovada' : 'submetida' ?>">
                <?= $pauta['fechada'] ? 'Fechada' : 'Aberta' ?>
            </span>
        </div>
    </div>
</div>

<?php if (empty($notas)): ?>
    <div class="alert alert-warning">Nenhum aluno elegível encontrado nesta pauta.</div>
<?php else: ?>

<?php if (!$pauta['fechada']): ?><form method="POST" action="<?= APP_URL ?>/funcionario/pauta-notas.php?id=<?= $pauta['id'] ?>"><?php endif; ?>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th><th>Aluno</th><th>Email</th>
                    <th>Nota Final (0–20)</th><th>Última edição</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($notas as $i => $n): ?>
                <tr>
                    <td style="color:var(--text-muted);font-size:.82rem;"><?= $i + 1 ?></td>
                    <td><strong style="font-size:.92rem;"><?= e($n['aluno_nome']) ?></strong></td>
                    <td style="color:var(--text-muted);font-size:.82rem;"><?= e($n['aluno_email']) ?></td>
                    <td>
                        <?php if (!$pauta['fechada']): ?>
                            <input type="number" name="notas[<?= $n['aluno_id'] ?>]"
                                   min="0" max="20" step="0.1"
                                   value="<?= $n['nota_final'] !== null ? e($n['nota_final']) : '' ?>"
                                   style="width:90px;">
                        <?php else: ?>
                            <strong style="font-family:'Playfair Display',serif;font-size:1.05rem;">
                                <?= $n['nota_final'] !== null ? e($n['nota_final']) : '—' ?>
                            </strong>
                        <?php endif; ?>
                    </td>
                    <td style="font-size:.78rem;color:var(--text-muted);">
                        <?= $n['editado_em'] ? formatDate($n['editado_em']) . ' · ' . e($n['editado_por_nome'] ?? '') : '—' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php if (!$pauta['fechada']): ?>
    <div class="actions" style="margin-top:1.25rem;">
        <button type="submit" name="acao" value="guardar" class="btn btn-primary">💾 Guardar Notas</button>
        <button type="submit" name="acao" value="fechar" class="btn btn-danger"
            onclick="return confirm('Fechar a pauta impede edições futuras. Confirma?')">🔒 Fechar Pauta</button>
    </div>
    <?php endif; ?>
</div>

<?php if (!$pauta['fechada']): ?></form><?php endif; ?>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

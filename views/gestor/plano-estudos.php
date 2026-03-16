<?php // views/gestor/plano-estudos.php
$pageTitle = 'Plano de Estudos — ' . $curso['nome'];
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Gestão Pedagógica · <?= e($curso['codigo']) ?></p>
        <h1>Plano de Estudos</h1>
    </div>
    <a href="<?= APP_URL ?>/gestor/cursos.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-title" style="justify-content:space-between;">
        <span><?= e($curso['nome']) ?></span>
        <span style="font-size:.78rem;font-weight:400;color:var(--text-muted);">
            <?= $curso['duracao_anos'] ?> ano(s) · <?= count($plano) ?> UC(s)
        </span>
    </div>

    <?php if (empty($plano)): ?>
        <p style="color:var(--text-muted);text-align:center;padding:1.5rem 0;font-size:.9rem;">Nenhuma UC associada ainda.</p>
    <?php else: ?>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr><th>Código</th><th>UC</th><th>Ano</th><th>Semestre</th><th>ECTS</th><th>Ações</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($plano as $row): ?>
                    <tr>
                        <td><strong style="color:var(--crimson);font-size:.78rem;letter-spacing:.05em;text-transform:uppercase;"><?= e($row['uc_codigo']) ?></strong></td>
                        <td><?= e($row['uc_nome']) ?></td>
                        <td><?= $row['ano'] ?>º Ano</td>
                        <td><?= $row['semestre'] ?>º Sem.</td>
                        <td><?= $row['creditos'] ?></td>
                        <td>
                            <a href="<?= APP_URL ?>/gestor/plano-remover.php?id=<?= $row['id'] ?>&curso_id=<?= $curso['id'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Remover esta UC do plano?')">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php if ($ucsDisponiveis): ?>
<div class="card">
    <div class="card-title">Adicionar UC ao Plano</div>
    <form method="POST" action="<?= APP_URL ?>/gestor/plano-estudos.php?curso_id=<?= $curso['id'] ?>">
        <div style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:1rem;align-items:end;">
            <div class="form-group" style="margin:0;">
                <label>Unidade Curricular *</label>
                <select name="uc_id" required>
                    <option value="">— Selecione —</option>
                    <?php foreach ($ucsDisponiveis as $uc): ?>
                        <option value="<?= $uc['id'] ?>"><?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Ano *</label>
                <select name="ano" required>
                    <?php for ($a = 1; $a <= $curso['duracao_anos']; $a++): ?>
                        <option value="<?= $a ?>"><?= $a ?>º Ano</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Semestre *</label>
                <select name="semestre" required>
                    <option value="1">1º Sem.</option>
                    <option value="2">2º Sem.</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </form>
</div>
<?php else: ?>
    <div class="alert alert-info">Todas as UCs disponíveis já estão associadas a este curso.</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

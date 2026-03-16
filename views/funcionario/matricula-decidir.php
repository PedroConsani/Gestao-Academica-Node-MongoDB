<?php // views/funcionario/matricula-decidir.php
$pageTitle = 'Analisar Matrícula #' . $matricula['id'];
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Serviços Académicos</p>
        <h1>Pedido de Matrícula #<?= $matricula['id'] ?></h1>
    </div>
    <a href="<?= APP_URL ?>/funcionario/matriculas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Dados do Pedido</div>
        <table style="width:100%;font-size:.9rem;">
            <tbody>
                <?php foreach ([
                    'Aluno'      => $matricula['aluno_nome'],
                    'Email'      => $matricula['aluno_email'],
                    'Curso'      => $matricula['curso_nome'],
                    'Ano Letivo' => $matricula['ano_letivo'],
                    'Submetido'  => formatDate($matricula['criado_em']),
                ] as $k => $v): ?>
                <tr>
                    <td style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;
                               color:var(--text-muted);padding:.5rem 0;width:35%;border-bottom:1px solid var(--border);">
                        <?= $k ?>
                    </td>
                    <td style="padding:.5rem 0;border-bottom:1px solid var(--border);"><?= e($v) ?></td>
                </tr>
                <?php endforeach; ?>
                <tr>
                    <td style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;
                               color:var(--text-muted);padding:.5rem 0;">Estado</td>
                    <td style="padding:.5rem 0;">
                        <span class="badge badge-<?= $matricula['estado'] ?>"><?= ucfirst($matricula['estado']) ?></span>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php if ($matricula['observacoes_aluno']): ?>
            <div class="alert alert-info" style="margin-top:1rem;">
                <strong>Nota do aluno:</strong> <?= e($matricula['observacoes_aluno']) ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($matricula['estado'] !== 'pendente'): ?>
    <div class="card">
        <div class="card-title">Decisão Registada</div>
        <table style="width:100%;font-size:.9rem;">
            <tbody>
                <?php foreach ([
                    'Decisão'    => ucfirst($matricula['estado']),
                    'Decidido por' => $matricula['decidido_por_nome'] ?? '—',
                    'Em'         => formatDate($matricula['decidido_em']),
                ] as $k => $v): ?>
                <tr>
                    <td style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;
                               color:var(--text-muted);padding:.5rem 0;width:40%;border-bottom:1px solid var(--border);">
                        <?= $k ?>
                    </td>
                    <td style="padding:.5rem 0;border-bottom:1px solid var(--border);"><?= e($v) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if ($matricula['observacoes_func']): ?>
            <div class="alert alert-<?= $matricula['estado'] === 'aprovada' ? 'success' : 'error' ?>" style="margin-top:1rem;">
                <strong>Observações:</strong> <?= e($matricula['observacoes_func']) ?>
            </div>
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
            <label>Observações / Justificação</label>
            <textarea name="observacoes" rows="3"
                      placeholder="Motivo da decisão..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
        </div>
        <div class="actions">
            <button type="submit" name="decisao" value="aprovada" class="btn btn-success"
                onclick="return confirm('Confirma a aprovação?')">✅ Aprovar</button>
            <button type="submit" name="decisao" value="rejeitada" class="btn btn-danger"
                onclick="return confirm('Confirma a rejeição?')">❌ Rejeitar</button>
        </div>
    </form>
</div>
<?php endif; ?>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

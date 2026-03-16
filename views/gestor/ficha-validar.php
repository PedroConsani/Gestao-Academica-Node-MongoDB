<?php // views/gestor/ficha-validar.php
$pageTitle = 'Validar Ficha';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Gestão Pedagógica</p>
        <h1>Ficha de <?= e($ficha['aluno_nome']) ?></h1>
    </div>
    <a href="<?= APP_URL ?>/gestor/fichas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-title">Dados Pessoais</div>
        <?php if ($ficha['foto_path']): ?>
            <div style="margin-bottom:1.25rem;">
                <img src="<?= e(\UploadHelper::fotoUrl($ficha['foto_path'])) ?>" class="foto-preview">
            </div>
        <?php endif; ?>
        <?php foreach ([
            'Nome'         => $ficha['aluno_nome'],
            'Email'        => $ficha['aluno_email'],
            'Nascimento'   => $ficha['data_nascimento'] ? date('d/m/Y', strtotime($ficha['data_nascimento'])) : '—',
            'Nacionalidade'=> $ficha['nacionalidade'] ?? '—',
            'NIF'          => $ficha['nif'] ?? '—',
            'CC'           => $ficha['cc'] ?? '—',
            'Telefone'     => $ficha['telefone'] ?? '—',
            'Morada'       => ($ficha['morada'] ?? '—') . ', ' . ($ficha['codigo_postal'] ?? '') . ' ' . ($ficha['localidade'] ?? ''),
            'Curso'        => $ficha['curso_nome'] ?? 'Não selecionado',
        ] as $k => $v): ?>
        <div style="display:flex;gap:1rem;padding:.5rem 0;border-bottom:1px solid var(--border);font-size:.9rem;">
            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;
                         color:var(--text-muted);min-width:110px;padding-top:.15rem;"><?= $k ?></span>
            <span><?= e($v) ?></span>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="card">
        <div class="card-title">Estado e Validação</div>
        <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem;">
            <span style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--text-muted);">Estado</span>
            <span class="badge badge-<?= $ficha['estado'] ?>"><?= ucfirst($ficha['estado']) ?></span>
        </div>
        <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:1rem;">
            Submetida em <?= formatDate($ficha['submetida_em']) ?>
        </div>

        <?php if ($ficha['validada_por']): ?>
            <div style="font-size:.82rem;color:var(--text-muted);margin-bottom:1rem;">
                Validada por <strong><?= e($ficha['validada_por_nome']) ?></strong>
                em <?= formatDate($ficha['validada_em']) ?>
            </div>
        <?php endif; ?>

        <?php if ($ficha['observacoes']): ?>
            <div class="alert alert-<?= $ficha['estado'] === 'aprovada' ? 'success' : 'error' ?>">
                <strong>Observações:</strong> <?= e($ficha['observacoes']) ?>
            </div>
        <?php endif; ?>

        <?php if ($ficha['estado'] === 'submetida'): ?>
            <hr style="margin:1.25rem 0;border-color:var(--border);">
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error"><?= e(implode(' ', $errors)) ?></div>
            <?php endif; ?>
            <form method="POST" action="<?= APP_URL ?>/gestor/ficha-validar.php?id=<?= $ficha['id'] ?>">
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
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

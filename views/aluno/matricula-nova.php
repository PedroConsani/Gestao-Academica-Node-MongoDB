<?php // views/aluno/matricula-nova.php
$pageTitle = 'Nova Matrícula';
ob_start(); ?>

<div class="page-header">
    <h1>📝 Novo Pedido de Matrícula</h1>
    <a href="<?= APP_URL ?>/aluno/matriculas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $e): ?><p><?= e($e) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="card">
    <?php 
    $selectedCursoId = $_POST['curso_id'] ?? ($_GET['curso'] ?? null);
    ?>
    
    <div class="form-group">
        <label>Cursos Disponíveis</label>
        <?php if (empty($cursos)): ?>
            <p class="alert alert-info">Nenhum curso disponível no momento.</p>
        <?php else: ?>
            <?php foreach ($cursos as $c): 
                $temUcs = !empty($ucsPorCurso[$c['id']]);
            ?>
                <div class="card mb-2 course-preview <?= $selectedCursoId == $c['id'] ? 'selected' : '' ?>">
                    <details <?= $selectedCursoId == $c['id'] ? 'open' : '' ?>>
                        <summary onclick="this.parentElement.classList.toggle('selected')">
                            <strong><?= e($c['nome']) ?> (<?= e($c['codigo']) ?>)</strong>
                            <small style="float:right; color:var(--text-muted);">
                                <?= $temUcs ? count($ucsPorCurso[$c['id']]) . ' UCs' : 'Sem plano curricular' ?>
                            </small>
                        </summary>
                        <?php if (!$temUcs): ?>
                            <div style="padding:1rem; color:var(--text-muted);">Curso sem unidades curriculares definidas.</div>
                        <?php else: ?>
                            <div style="padding:1.5rem;">
                                <h4 style="margin-top:0;">Unidades Curriculares</h4>
                                <div class="uc-list">
                                    <?php foreach ($ucsPorCurso[$c['id']] as $uc): ?>
                                        <div class="uc-item" style="display:flex; justify-content:space-between; align-items:center; padding:.75rem; border-left:3px solid var(--primary); margin-bottom:.5rem; background:var(--bg-light);">
                                            <div>
                                                <strong><?= e($uc['codigo']) ?> — <?= e($uc['nome']) ?></strong>
                                                <?php if ($uc['descricao']): ?>
                                                    <br><small><?= e(substr($uc['descricao'], 0, 100)) ?>...</small>
                                                <?php endif; ?>
                                            </div>
                                            <div style="text-align:right;">
                                                <span class="badge bg-secondary"><?= $uc['creditos'] ?> ECTS</span><br>
                                                <small>Ano <?= $uc['primeiro_ano'] ?>/Sem <?= $uc['primeiro_semestre'] ?></small>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($selectedCursoId == $c['id']): ?>
                            <form method="POST" action="" class="mt-3 p-3" style="background:var(--bg-light); border-radius:0 0 .5rem .5rem;">
                                <input type="hidden" name="curso_id" value="<?= $c['id'] ?>">
                                <div class="form-group">
                                    <label>Ano Letivo *</label>
                                    <input type="text" name="ano_letivo" placeholder="ex: 2024/2025" maxlength="9"
                                           value="<?= e($_POST['ano_letivo'] ?? date('Y') . '/' . (date('Y')+1)) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label>Observações (opcional)</label>
                                    <textarea name="observacoes" rows="3" placeholder="Informação adicional..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submeter Pedido para este Curso</button>
                            </form>
                        <?php else: ?>
                            <div class="text-center p-3">
                                <a href="?curso=<?= $c['id'] ?>" class="btn btn-primary">Selecionar este Curso →</a>
                            </div>
                        <?php endif; ?>
                    </details>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';

<?php // views/aluno/matricula-nova.php
$pageTitle = 'Nova Matrícula';
ob_start(); ?>

<div class="page-header">
    <div>
        <p>Portal Académico do Aluno</p>
        <h1>Novo Pedido de Matrícula</h1>
    </div>
    <a href="<?= APP_URL ?>/aluno/matriculas.php" class="btn btn-secondary btn-sm">← Voltar</a>
</div>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <?php foreach ($errors as $err): ?><p><?= e($err) ?></p><?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Indicador de passos -->
<div class="mat-steps">
    <div class="mat-step active" id="step-1">
        <div class="mat-step-num">1</div>
        <span>Escolher Curso</span>
    </div>
    <div class="mat-step-line"></div>
    <div class="mat-step" id="step-2">
        <div class="mat-step-num">2</div>
        <span>Confirmar e Submeter</span>
    </div>
</div>

<!-- ── PASSO 1: Escolha do curso ─────────────────────────── -->
<div id="passo1">
    <?php if (empty($cursos)): ?>
        <div class="alert alert-info">Nenhum curso disponível no momento.</div>
    <?php else: ?>
        <div class="courses-grid">
            <?php foreach ($cursos as $c):
                $ucs       = $ucsPorCurso[$c['id']] ?? [];
                $numUcs    = count($ucs);
                $bloqueado = in_array($c['id'], $cursosJaMatriculados ?? []);
            ?>

            <div class="course-card <?= $bloqueado ? 'blocked' : '' ?>"
                 id="card-<?= $c['id'] ?>"
                 data-curso-id="<?= $c['id'] ?>"
                 data-curso-nome="<?= e($c['nome']) ?>">

                <!-- Cabeçalho clicável -->
                <div class="course-header"
                     onclick="<?= $bloqueado ? 'void(0)' : 'toggleCurso(' . $c['id'] . ')' ?>"
                     style="<?= $bloqueado ? 'cursor:default;opacity:.75;' : '' ?>">
                    <div style="flex:1;min-width:0;">
                        <h3 class="course-title"><?= e($c['nome']) ?></h3>
                        <p class="course-code"><?= e($c['codigo']) ?> &nbsp;·&nbsp; <?= $c['duracao_anos'] ?> ano(s)</p>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:.3rem;flex-shrink:0;">
                        <?php if ($bloqueado): ?>
                            <span style="background:rgba(255,255,255,.25);color:#fff;padding:.15rem .5rem;
                                         border-radius:3px;font-size:.7rem;font-weight:700;letter-spacing:.05em;">
                                JÁ MATRICULADO
                            </span>
                        <?php else: ?>
                            <span class="uc-count"><?= $numUcs ?> UC<?= $numUcs !== 1 ? 's' : '' ?></span>
                            <svg class="course-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                 stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Corpo expansível (grid-rows trick — sem max-height limitado) -->
                <div class="course-body">
                    <div class="course-body-inner">

                        <?php if (empty($ucs)): ?>
                            <p class="no-ucs">Sem plano curricular definido.</p>
                        <?php else: ?>
                            <?php
                            $porAno = [];
                            foreach ($ucs as $uc) {
                                $k = ($uc['primeiro_ano'] ?? '?') . '-' . ($uc['primeiro_semestre'] ?? '?');
                                $porAno[$k][] = $uc;
                            }
                            ksort($porAno);
                            ?>
                            <?php foreach ($porAno as $key => $grupo):
                                [$ano, $sem] = explode('-', $key);
                            ?>
                                <div class="uc-group-label">
                                    <?= $ano ?>º Ano &nbsp;·&nbsp; <?= $sem ?>º Semestre
                                </div>
                                <div class="ucs-grid">
                                    <?php foreach ($grupo as $uc): ?>
                                    <div class="uc-card">
                                        <strong><?= e($uc['codigo']) ?></strong>
                                        <span><?= e($uc['nome']) ?></span>
                                        <div class="uc-meta">
                                            <span class="ects"><?= $uc['creditos'] ?> ECTS</span>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <!-- Botão de selecção ou aviso de bloqueio -->
                        <div style="margin-top:1.25rem;padding-top:1rem;border-top:1px solid var(--border);">
                            <?php if ($bloqueado): ?>
                                <div class="alert alert-warning" style="margin:0;font-size:.85rem;">
                                    ⚠️ Já tem uma matrícula <strong>pendente ou aprovada</strong> neste curso.
                                    Só pode submeter nova matrícula após rejeição.
                                </div>
                            <?php else: ?>
                                <button type="button"
                                        class="btn btn-primary"
                                        style="width:100%;justify-content:center;"
                                        onclick="selectCourse(<?= $c['id'] ?>)">
                                    Selecionar este curso →
                                </button>
                            <?php endif; ?>
                        </div>

                    </div><!-- /course-body-inner -->
                </div><!-- /course-body -->

            </div><!-- /course-card -->

            <?php endforeach; ?>
        </div><!-- /courses-grid -->
    <?php endif; ?>
</div><!-- /passo1 -->

<!-- ── PASSO 2: Confirmação ──────────────────────────────── -->
<div id="passo2" style="display:none;">
    <div class="card" style="border-top:3px solid var(--crimson);">
        <div style="display:flex;align-items:center;justify-content:space-between;
                    margin-bottom:1.5rem;gap:1rem;flex-wrap:wrap;">
            <div>
                <p style="font-size:.72rem;font-weight:700;text-transform:uppercase;
                           letter-spacing:.1em;color:var(--crimson);margin-bottom:.3rem;">
                    Curso selecionado
                </p>
                <h2 id="confirmNome"
                    style="font-family:'Playfair Display',Georgia,serif;
                           font-size:1.3rem;font-weight:700;"></h2>
            </div>
            <button type="button" onclick="voltarPasso1()"
                    class="btn btn-secondary btn-sm">← Mudar curso</button>
        </div>

        <form method="POST" action="<?= APP_URL ?>/aluno/matricula-nova.php">
            <input type="hidden" name="curso_id" id="formCursoId" value="">
            <div class="form-row">
                <div class="form-group">
                    <label>Ano Letivo *</label>
                    <input type="text" name="ano_letivo" placeholder="ex: 2024/2025"
                           maxlength="9" required
                           value="<?= e($_POST['ano_letivo'] ?? date('Y') . '/' . (date('Y') + 1)) ?>">
                </div>
                <div class="form-group">
                    <label>Observações
                        <span style="font-weight:400;text-transform:none;letter-spacing:0;">(opcional)</span>
                    </label>
                    <textarea name="observacoes" rows="2"
                              placeholder="Informação adicional..."><?= e($_POST['observacoes'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="actions" style="margin-top:.5rem;">
                <button type="submit" class="btn btn-primary" style="padding:.65rem 2rem;">
                    📤 Submeter Pedido de Matrícula
                </button>
            </div>
        </form>
    </div>
</div><!-- /passo2 -->

<style>
/* ── Steps ── */
.mat-steps {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}
.mat-step {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-size: .82rem;
    font-weight: 600;
    color: var(--text-muted);
}
.mat-step.active { color: var(--crimson); }
.mat-step.done   { color: var(--success); }
.mat-step-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: var(--border);
    color: var(--text-muted);
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 700; flex-shrink: 0;
    transition: all .3s;
}
.mat-step.active .mat-step-num { background: var(--crimson); color: #fff; box-shadow: 0 0 0 4px var(--crimson-bg); }
.mat-step.done   .mat-step-num { background: var(--success);  color: #fff; }
.mat-step-line { flex: 1; height: 2px; background: var(--border); margin: 0 .75rem; transition: background .3s; }
.mat-step-line.done { background: var(--success); }

/* ── Course cards ── */
.courses-grid {
    display: flex;
    flex-direction: column;
    gap: .75rem;
}
.course-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: 10px;
    overflow: hidden;        /* precisa estar hidden para o border-radius funcionar */
    transition: border-color .2s, box-shadow .2s;
}
.course-card:hover  { border-color: rgba(165,28,48,.4); box-shadow: 0 4px 16px rgba(0,0,0,.07); }
.course-card.open   { border-color: var(--crimson); }
.course-card.blocked { opacity: .65; pointer-events: none; }
.course-card.blocked .course-header { background: var(--text-muted); cursor: default; }

.course-header {
    padding: 1rem 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    cursor: pointer;
    background: var(--crimson);
    color: #fff;
    user-select: none;
}
.course-title { font-family: 'Playfair Display',Georgia,serif; font-size:.98rem; font-weight:700; margin:0 0 .15rem; }
.course-code  { font-size:.75rem; opacity:.82; margin:0; }
.uc-count     { font-size:.75rem; font-weight:700; opacity:.85; }

.course-chevron {
    width: 18px; height: 18px;
    transition: transform .35s cubic-bezier(.4,0,.2,1);
    flex-shrink: 0;
}
.course-card.open .course-chevron { transform: rotate(180deg); }

/* Animação com grid-template-rows — expande sem limite de altura */
.course-body {
    display: grid;
    grid-template-rows: 0fr;
    transition: grid-template-rows .35s cubic-bezier(.4,0,.2,1);
    background: var(--surface);
}
.course-card.open .course-body {
    grid-template-rows: 1fr;
}
.course-body-inner {
    overflow: hidden;
    padding: 0 1.25rem;
    /* padding animated via the grid trick — não transiciona sozinho */
}
.course-card.open .course-body-inner {
    padding: 1.25rem;
}

/* UCs */
.uc-group-label {
    font-size: .7rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: .1em; color: var(--crimson);
    margin: 1rem 0 .5rem;
    padding-bottom: .3rem;
    border-bottom: 1px solid var(--crimson-bg);
}
.uc-group-label:first-child { margin-top: 0; }
</style>

<script>
function toggleCurso(id) {
    const card = document.getElementById('card-' + id);
    const isOpen = card.classList.contains('open');

    // Fechar TODOS primeiro
    document.querySelectorAll('.course-card').forEach(function(c) {
        c.classList.remove('open');
    });

    // Só abrir este se estava fechado
    if (!isOpen) {
        card.classList.add('open');
    }
}

function selectCourse(cursoId) {
    const card = document.getElementById('card-' + cursoId);

    document.getElementById('formCursoId').value = cursoId;
    document.getElementById('confirmNome').textContent = card.dataset.cursoNome;

    document.getElementById('passo1').style.display = 'none';
    document.getElementById('passo2').style.display = 'block';

    // Atualizar steps
    var s1 = document.getElementById('step-1');
    s1.classList.remove('active');
    s1.classList.add('done');
    s1.querySelector('.mat-step-num').textContent = '✓';
    document.querySelector('.mat-step-line').classList.add('done');
    document.getElementById('step-2').classList.add('active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function voltarPasso1() {
    document.getElementById('passo2').style.display = 'none';
    document.getElementById('passo1').style.display = 'block';

    // Reset steps
    var s1 = document.getElementById('step-1');
    s1.classList.add('active');
    s1.classList.remove('done');
    s1.querySelector('.mat-step-num').textContent = '1';
    document.querySelector('.mat-step-line').classList.remove('done');
    document.getElementById('step-2').classList.remove('active');

    window.scrollTo({ top: 0, behavior: 'smooth' });
}
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
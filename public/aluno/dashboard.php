<?php
// public/aluno/dashboard.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$fichaModel     = new FichaAlunoModel();
$matriculaModel = new MatriculaModel();
$userId         = currentUser()['id'];
$pdo            = getDB();

$ficha      = $fichaModel->findByAluno($userId);
$matriculas = $matriculaModel->findByAluno($userId);

$fichaEstado         = $ficha['estado'] ?? null;
$totalMatriculas     = count($matriculas);
$matriculasAprovadas = count(array_filter($matriculas, fn($m) => $m['estado'] === MATRICULA_APROVADA));

// Notas do aluno
$stmt = $pdo->prepare("
    SELECT
        n.nota_final,
        uc.nome       AS uc_nome,
        uc.codigo     AS uc_codigo,
        c.nome        AS curso_nome,
        p.ano_letivo,
        p.epoca
    FROM notas n
    JOIN pautas p                  ON p.id  = n.pauta_id
    JOIN unidades_curriculares uc  ON uc.id = p.uc_id
    JOIN cursos c                  ON c.id  = p.curso_id
    WHERE n.aluno_id = ?
    ORDER BY p.ano_letivo DESC, p.epoca
");
$stmt->execute([$userId]);
$todasNotas   = $stmt->fetchAll();
$totalNotas   = count(array_filter($todasNotas, fn($n) => $n['nota_final'] !== null));
$ultimasNotas = array_slice($todasNotas, 0, 5);

include __DIR__ . '/../../views/aluno/dashboard.php';
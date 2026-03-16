<?php
// public/aluno/notas-curso.php
require_once __DIR__ . '/../../config/bootstrap.php';
requireRole(ROLE_ALUNO);

$cursoId = (int) ($_GET['id'] ?? 0);
if ($cursoId === 0) {
    flash('error', 'Curso não especificado.');
    redirect(APP_URL . '/aluno/dashboard.php');
}

$userId = currentUser()['id'];
$cursoModel = new CursoModel();
$ucModel = new UCModel();
$matriculaModel = new MatriculaModel();
$pdo = getDB();

$curso = $cursoModel->findById($cursoId);
if (!$curso) {
    flash('error', 'Curso não encontrado.');
    redirect(APP_URL . '/aluno/dashboard.php');
}

// Verificar se tem matrícula aprovada no curso
$stmt = $pdo->prepare("
    SELECT * FROM matriculas m 
    JOIN cursos c ON c.id = m.curso_id
    WHERE m.aluno_id = ? AND m.curso_id = ? AND m.estado = 'aprovada'
");
$stmt->execute([$userId, $cursoId]);
$matricula = $stmt->fetch();
if (!$matricula) {
    flash('error', 'Não tem matrícula aprovada neste curso.');
    redirect(APP_URL . '/aluno/dashboard.php');
}

// UCs do curso
$ucs = $ucModel->getByCurso($cursoId);

// Notas do aluno para este curso (todas épocas/pautas)
$stmt = $pdo->prepare("
    SELECT n.nota_final, p.epoca, p.ano_letivo, uc.nome AS uc_nome, uc.codigo AS uc_codigo
    FROM notas n
    JOIN pautas p ON p.id = n.pauta_id
    JOIN unidades_curriculares uc ON uc.id = p.uc_id
    WHERE n.aluno_id = ? AND p.curso_id = ?
    ORDER BY uc.codigo, p.epoca
");
$stmt->execute([$userId, $cursoId]);
$notasRaw = $stmt->fetchAll();

// Group notes by UC
$notasPorUC = [];
foreach ($notasRaw as $nota) {
    $nota['uc_id'] = array_search($nota['uc_codigo'], array_column($ucs, 'codigo'));
    $notasPorUC[$nota['uc_id']][] = $nota;
}

include __DIR__ . '/../../views/aluno/notas-curso.php';
?>


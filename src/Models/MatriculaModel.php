<?php
// src/Models/MatriculaModel.php

class MatriculaModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByAluno(int $alunoId): array {
        $stmt = $this->db->prepare("
            SELECT m.*, c.nome AS curso_nome, u.nome AS decidido_por_nome
            FROM matriculas m
            JOIN cursos c ON c.id = m.curso_id
            LEFT JOIN utilizadores u ON u.id = m.decidido_por
            WHERE m.aluno_id = ?
            ORDER BY m.criado_em DESC
        ");
        $stmt->execute([$alunoId]);
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT m.*, c.nome AS curso_nome,
                   a.nome AS aluno_nome, a.email AS aluno_email,
                   f.nome AS decidido_por_nome
            FROM matriculas m
            JOIN cursos c ON c.id = m.curso_id
            JOIN utilizadores a ON a.id = m.aluno_id
            LEFT JOIN utilizadores f ON f.id = m.decidido_por
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function allPendentes(): array {
        $stmt = $this->db->query("
            SELECT m.*, c.nome AS curso_nome, a.nome AS aluno_nome, COALESCE(a.email, '') AS aluno_email
            FROM matriculas m
            JOIN cursos c ON c.id = m.curso_id
            JOIN utilizadores a ON a.id = m.aluno_id
            WHERE m.estado = 'pendente'
            ORDER BY m.criado_em ASC
        ");
        return $stmt->fetchAll();
    }

    public function all(): array {
        $stmt = $this->db->query("
            SELECT m.*, c.nome AS curso_nome, a.nome AS aluno_nome, COALESCE(a.email, '') AS aluno_email,
                   f.nome AS decidido_por_nome
            FROM matriculas m
            JOIN cursos c ON c.id = m.curso_id
            JOIN utilizadores a ON a.id = m.aluno_id
            LEFT JOIN utilizadores f ON f.id = m.decidido_por
            ORDER BY m.criado_em DESC
        ");
        return $stmt->fetchAll();
    }

    public function create(int $alunoId, int $cursoId, string $anoLetivo, string $obs = ''): int {
        $stmt = $this->db->prepare("
            INSERT INTO matriculas (aluno_id, curso_id, ano_letivo, observacoes_aluno, estado)
            VALUES (:aluno_id, :curso_id, :ano_letivo, :obs, 'pendente')
        ");
        $stmt->execute([
            ':aluno_id'  => $alunoId,
            ':curso_id'  => $cursoId,
            ':ano_letivo' => $anoLetivo,
            ':obs'       => $obs,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function decidir(int $id, string $decisao, string $obs, int $funcId): bool {
        $stmt = $this->db->prepare("
            UPDATE matriculas
            SET estado = :estado, observacoes_func = :obs,
                decidido_por = :func, decidido_em = NOW()
            WHERE id = :id AND estado = 'pendente'
        ");
        return $stmt->execute([
            ':estado' => $decisao,
            ':obs'    => $obs,
            ':func'   => $funcId,
            ':id'     => $id,
        ]) && $stmt->rowCount() > 0;
    }

    // Alunos aprovados num curso/ano letivo (para pautas)
    public function alunosAprovadosCurso(int $cursoId, string $anoLetivo): array {
        $stmt = $this->db->prepare("
            SELECT u.id, u.nome, u.email
            FROM matriculas m
            JOIN utilizadores u ON u.id = m.aluno_id
            WHERE m.curso_id = ? AND m.ano_letivo = ? AND m.estado = 'aprovada'
            ORDER BY u.nome
        ");
        $stmt->execute([$cursoId, $anoLetivo]);
        return $stmt->fetchAll();
    }
}

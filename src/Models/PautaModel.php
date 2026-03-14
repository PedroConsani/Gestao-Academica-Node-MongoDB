<?php
// src/Models/PautaModel.php

class PautaModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function all(): array {
        $stmt = $this->db->query("
            SELECT p.*, uc.nome AS uc_nome, uc.codigo AS uc_codigo,
                   c.nome AS curso_nome, u.nome AS criada_por_nome
            FROM pautas p
            JOIN unidades_curriculares uc ON uc.id = p.uc_id
            JOIN cursos c ON c.id = p.curso_id
            JOIN utilizadores u ON u.id = p.criada_por
            ORDER BY p.criada_em DESC
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT p.*, uc.nome AS uc_nome, uc.codigo AS uc_codigo,
                   c.nome AS curso_nome, u.nome AS criada_por_nome
            FROM pautas p
            JOIN unidades_curriculares uc ON uc.id = p.uc_id
            JOIN cursos c ON c.id = p.curso_id
            JOIN utilizadores u ON u.id = p.criada_por
            WHERE p.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(int $ucId, int $cursoId, string $anoLetivo, string $epoca, int $userId): int {
        $stmt = $this->db->prepare("
            INSERT INTO pautas (uc_id, curso_id, ano_letivo, epoca, criada_por)
            VALUES (:uc_id, :curso_id, :ano_letivo, :epoca, :criada_por)
        ");
        $stmt->execute([
            ':uc_id'     => $ucId,
            ':curso_id'  => $cursoId,
            ':ano_letivo' => $anoLetivo,
            ':epoca'     => $epoca,
            ':criada_por' => $userId,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function getNotas(int $pautaId): array {
        $stmt = $this->db->prepare("
            SELECT n.*, u.nome AS aluno_nome, u.email AS aluno_email,
                   e.nome AS editado_por_nome
            FROM notas n
            JOIN utilizadores u ON u.id = n.aluno_id
            LEFT JOIN utilizadores e ON e.id = n.editado_por
            WHERE n.pauta_id = ?
            ORDER BY u.nome
        ");
        $stmt->execute([$pautaId]);
        return $stmt->fetchAll();
    }

    public function adicionarAlunos(int $pautaId, array $alunoIds): void {
        $stmt = $this->db->prepare("
            INSERT IGNORE INTO notas (pauta_id, aluno_id) VALUES (?, ?)
        ");
        foreach ($alunoIds as $alunoId) {
            $stmt->execute([$pautaId, $alunoId]);
        }
    }

    public function registarNota(int $pautaId, int $alunoId, ?float $nota, int $funcId): bool {
        $stmt = $this->db->prepare("
            UPDATE notas
            SET nota_final = :nota, editado_por = :func, editado_em = NOW()
            WHERE pauta_id = :pauta_id AND aluno_id = :aluno_id
        ");
        return $stmt->execute([
            ':nota'     => $nota,
            ':func'     => $funcId,
            ':pauta_id' => $pautaId,
            ':aluno_id' => $alunoId,
        ]);
    }

    public function fecharPauta(int $id): bool {
        $stmt = $this->db->prepare("UPDATE pautas SET fechada = 1 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function exists(int $ucId, int $cursoId, string $anoLetivo, string $epoca): bool {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM pautas
            WHERE uc_id = ? AND curso_id = ? AND ano_letivo = ? AND epoca = ?
        ");
        $stmt->execute([$ucId, $cursoId, $anoLetivo, $epoca]);
        return (bool) $stmt->fetchColumn();
    }
}

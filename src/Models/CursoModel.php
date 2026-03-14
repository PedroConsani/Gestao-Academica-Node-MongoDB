<?php
// src/Models/CursoModel.php

class CursoModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function all(bool $apenasAtivos = false): array {
        $where = $apenasAtivos ? 'WHERE c.ativo = 1' : '';
        $stmt = $this->db->query("
            SELECT c.*, u.nome AS criado_por_nome
            FROM cursos c
            JOIN utilizadores u ON u.id = c.criado_por
            $where
            ORDER BY c.nome
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM cursos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data, int $userId): int {
        $stmt = $this->db->prepare("
            INSERT INTO cursos (nome, codigo, descricao, duracao_anos, criado_por)
            VALUES (:nome, :codigo, :descricao, :duracao_anos, :criado_por)
        ");
        $stmt->execute([
            ':nome'         => $data['nome'],
            ':codigo'       => strtoupper($data['codigo']),
            ':descricao'    => $data['descricao'] ?? null,
            ':duracao_anos' => $data['duracao_anos'],
            ':criado_por'   => $userId,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE cursos
            SET nome = :nome, codigo = :codigo, descricao = :descricao, duracao_anos = :duracao_anos
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nome'         => $data['nome'],
            ':codigo'       => strtoupper($data['codigo']),
            ':descricao'    => $data['descricao'] ?? null,
            ':duracao_anos' => $data['duracao_anos'],
            ':id'           => $id,
        ]);
    }

    public function toggleAtivo(int $id): bool {
        $stmt = $this->db->prepare("UPDATE cursos SET ativo = NOT ativo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function codigoExists(string $codigo, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM cursos WHERE codigo = ?";
        $params = [strtoupper($codigo)];
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        return (bool) $this->db->prepare($sql)->execute($params) && $this->db->query("SELECT FOUND_ROWS()")->fetchColumn();
    }
}

<?php
// src/Models/UCModel.php

class UCModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function all(bool $apenasAtivas = false): array {
        $where = $apenasAtivas ? 'WHERE uc.ativo = 1' : '';
        $stmt = $this->db->query("
            SELECT uc.*, u.nome AS criado_por_nome
            FROM unidades_curriculares uc
            JOIN utilizadores u ON u.id = uc.criado_por
            $where
            ORDER BY uc.nome
        ");
        return $stmt->fetchAll();
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM unidades_curriculares WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(array $data, int $userId): int {
        $stmt = $this->db->prepare("
            INSERT INTO unidades_curriculares (nome, codigo, descricao, creditos, criado_por)
            VALUES (:nome, :codigo, :descricao, :creditos, :criado_por)
        ");
        $stmt->execute([
            ':nome'      => $data['nome'],
            ':codigo'    => strtoupper($data['codigo']),
            ':descricao' => $data['descricao'] ?? null,
            ':creditos'  => $data['creditos'],
            ':criado_por' => $userId,
        ]);
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE unidades_curriculares
            SET nome = :nome, codigo = :codigo, descricao = :descricao, creditos = :creditos
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nome'      => $data['nome'],
            ':codigo'    => strtoupper($data['codigo']),
            ':descricao' => $data['descricao'] ?? null,
            ':creditos'  => $data['creditos'],
            ':id'        => $id,
        ]);
    }

    public function toggleAtivo(int $id): bool {
        $stmt = $this->db->prepare("UPDATE unidades_curriculares SET ativo = NOT ativo WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // UCs não associadas a um curso
    public function notInCurso(int $cursoId): array {
        $stmt = $this->db->prepare("
            SELECT * FROM unidades_curriculares
            WHERE ativo = 1
              AND id NOT IN (SELECT uc_id FROM plano_estudos WHERE curso_id = ?)
            ORDER BY nome
        ");
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }
}

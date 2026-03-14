<?php
// src/Models/PlanoEstudosModel.php

class PlanoEstudosModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getByCurso(int $cursoId): array {
        $stmt = $this->db->prepare("
            SELECT pe.*, uc.nome AS uc_nome, uc.codigo AS uc_codigo, uc.creditos
            FROM plano_estudos pe
            JOIN unidades_curriculares uc ON uc.id = pe.uc_id
            WHERE pe.curso_id = ?
            ORDER BY pe.ano, pe.semestre, uc.nome
        ");
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }

    public function addUC(int $cursoId, int $ucId, int $ano, int $semestre, bool $obrigatoria = true): bool {
        // Verificar duplicação
        if ($this->exists($cursoId, $ucId)) {
            return false;
        }
        $stmt = $this->db->prepare("
            INSERT INTO plano_estudos (curso_id, uc_id, ano, semestre, obrigatoria)
            VALUES (:curso_id, :uc_id, :ano, :semestre, :obrigatoria)
        ");
        return $stmt->execute([
            ':curso_id'   => $cursoId,
            ':uc_id'      => $ucId,
            ':ano'        => $ano,
            ':semestre'   => $semestre,
            ':obrigatoria' => (int) $obrigatoria,
        ]);
    }

    public function removeUC(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM plano_estudos WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function exists(int $cursoId, int $ucId): bool {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM plano_estudos WHERE curso_id = ? AND uc_id = ?");
        $stmt->execute([$cursoId, $ucId]);
        return (bool) $stmt->fetchColumn();
    }

    public function getUCsByCurso(int $cursoId): array {
        $stmt = $this->db->prepare("
            SELECT uc.id, uc.nome, uc.codigo, pe.ano, pe.semestre
            FROM plano_estudos pe
            JOIN unidades_curriculares uc ON uc.id = pe.uc_id
            WHERE pe.curso_id = ?
            ORDER BY pe.ano, pe.semestre
        ");
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }
}

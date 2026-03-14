<?php
// src/Models/FichaAlunoModel.php

class FichaAlunoModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByAluno(int $alunoId): ?array {
        $stmt = $this->db->prepare("
            SELECT fa.*, c.nome AS curso_nome, u.nome AS validada_por_nome
            FROM fichas_aluno fa
            LEFT JOIN cursos c ON c.id = fa.curso_id
            LEFT JOIN utilizadores u ON u.id = fa.validada_por
            WHERE fa.aluno_id = ?
        ");
        $stmt->execute([$alunoId]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("
            SELECT fa.*, u.nome AS aluno_nome, u.email AS aluno_email,
                   c.nome AS curso_nome, g.nome AS validada_por_nome
            FROM fichas_aluno fa
            JOIN utilizadores u ON u.id = fa.aluno_id
            LEFT JOIN cursos c ON c.id = fa.curso_id
            LEFT JOIN utilizadores g ON g.id = fa.validada_por
            WHERE fa.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function allSubmetidas(): array {
        $stmt = $this->db->query("
            SELECT fa.*, u.nome AS aluno_nome, u.email AS aluno_email, c.nome AS curso_nome
            FROM fichas_aluno fa
            JOIN utilizadores u ON u.id = fa.aluno_id
            LEFT JOIN cursos c ON c.id = fa.curso_id
            WHERE fa.estado IN ('submetida','aprovada','rejeitada')
            ORDER BY fa.submetida_em DESC
        ");
        return $stmt->fetchAll();
    }

    public function createOrUpdate(int $alunoId, array $data): bool {
        $ficha = $this->findByAluno($alunoId);
        if ($ficha) {
            // Só pode editar se estiver em rascunho ou rejeitada
            if (!in_array($ficha['estado'], [FICHA_RASCUNHO, FICHA_REJEITADA])) {
                return false;
            }
            $stmt = $this->db->prepare("
                UPDATE fichas_aluno
                SET curso_id = :curso_id, data_nascimento = :data_nascimento,
                    nacionalidade = :nacionalidade, nif = :nif, cc = :cc,
                    telefone = :telefone, morada = :morada,
                    codigo_postal = :codigo_postal, localidade = :localidade,
                    foto_path = COALESCE(:foto_path, foto_path),
                    estado = 'rascunho'
                WHERE aluno_id = :aluno_id
            ");
        } else {
            $stmt = $this->db->prepare("
                INSERT INTO fichas_aluno
                    (aluno_id, curso_id, data_nascimento, nacionalidade, nif, cc,
                     telefone, morada, codigo_postal, localidade, foto_path, estado)
                VALUES
                    (:aluno_id, :curso_id, :data_nascimento, :nacionalidade, :nif, :cc,
                     :telefone, :morada, :codigo_postal, :localidade, :foto_path, 'rascunho')
            ");
        }
        return $stmt->execute([
            ':aluno_id'       => $alunoId,
            ':curso_id'       => $data['curso_id'] ?: null,
            ':data_nascimento' => $data['data_nascimento'] ?: null,
            ':nacionalidade'  => $data['nacionalidade'] ?? null,
            ':nif'            => $data['nif'] ?? null,
            ':cc'             => $data['cc'] ?? null,
            ':telefone'       => $data['telefone'] ?? null,
            ':morada'         => $data['morada'] ?? null,
            ':codigo_postal'  => $data['codigo_postal'] ?? null,
            ':localidade'     => $data['localidade'] ?? null,
            ':foto_path'      => $data['foto_path'] ?? null,
        ]);
    }

    public function submeter(int $alunoId): bool {
        $stmt = $this->db->prepare("
            UPDATE fichas_aluno
            SET estado = 'submetida', submetida_em = NOW()
            WHERE aluno_id = ? AND estado IN ('rascunho','rejeitada')
        ");
        return $stmt->execute([$alunoId]) && $stmt->rowCount() > 0;
    }

    public function validar(int $fichaId, string $decisao, string $observacoes, int $gestorId): bool {
        $stmt = $this->db->prepare("
            UPDATE fichas_aluno
            SET estado = :estado, observacoes = :obs, validada_por = :gestor, validada_em = NOW()
            WHERE id = :id AND estado = 'submetida'
        ");
        return $stmt->execute([
            ':estado'  => $decisao,
            ':obs'     => $observacoes,
            ':gestor'  => $gestorId,
            ':id'      => $fichaId,
        ]) && $stmt->rowCount() > 0;
    }
}

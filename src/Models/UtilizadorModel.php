<?php
// src/Models/UtilizadorModel.php

class UtilizadorModel {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function findByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE email = ? AND ativo = 1");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT * FROM utilizadores WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public function create(string $nome, string $email, string $password, string $role): int {
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $this->db->prepare("
            INSERT INTO utilizadores (nome, email, password_hash, role)
            VALUES (:nome, :email, :hash, :role)
        ");
        $stmt->execute([':nome' => $nome, ':email' => $email, ':hash' => $hash, ':role' => $role]);
        return (int) $this->db->lastInsertId();
    }

    public function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public function listByRole(string $role): array {
        $stmt = $this->db->prepare("SELECT id, nome, email FROM utilizadores WHERE role = ? AND ativo = 1 ORDER BY nome");
        $stmt->execute([$role]);
        return $stmt->fetchAll();
    }
}

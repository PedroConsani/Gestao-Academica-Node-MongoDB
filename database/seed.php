<?php
// database/seed.php
// Executar uma vez: php database/seed.php

require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

$users = [
    ['Admin Gestor',    'gestor@academia.pt',     'gestor123',     'gestor'],
    ['Ana Funcionária', 'funcionario@academia.pt', 'func123',       'funcionario'],
    ['João Aluno',      'aluno@academia.pt',       'aluno123',      'aluno'],
    ['Pedro',           'pedro@academia.pt',       'senha123',      'gestor'],
    ['Gabriel',           'gabriel@academia.pt',       'func123',      'funcionario'],
];

$stmt = $pdo->prepare("
    INSERT IGNORE INTO utilizadores (nome, email, password_hash, role)
    VALUES (:nome, :email, :hash, :role)
");

foreach ($users as [$nome, $email, $pass, $role]) {
    $hash = password_hash($pass, PASSWORD_BCRYPT, ['cost' => 12]);
    $stmt->execute([':nome' => $nome, ':email' => $email, ':hash' => $hash, ':role' => $role]);
    echo "Inserido: $email (password: $pass)\n";
}

echo "\nSeed concluído.\n";

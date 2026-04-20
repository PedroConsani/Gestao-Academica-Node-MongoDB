<?php
// database/seed.php
// Executar uma vez: php database/seed.php

require_once __DIR__ . '/../config/database.php';

$pdo = getDB();

$users = [
    ['Aluno',      'aluno@academia.pt',       'aluno123',      'aluno'],
    ['Gestor',     'gestor@academia.pt',       'gestor123',      'gestor'],
    ['Funcionário', 'func@academia.pt',       'func123',      'funcionario'],
    ['Aluno 2',      'alunoteste2@academia.pt',       'aluno123',      'aluno'],
    ['Funcionário 2', 'functeste2@academia.pt',       'func123',      'funcionario'],
    ['Aluno 3',       'aluno3@academia.pt',          'aluno123',     'aluno'],
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

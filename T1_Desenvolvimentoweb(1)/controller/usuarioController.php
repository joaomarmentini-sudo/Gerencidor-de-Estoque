<?php
require_once __DIR__ . '/../config.php';

function cadastrarUsuario($nome, $email, $senha, $tipo = 'funcionario') {
    global $pdo;

    try {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senhaHash, $tipo]);
        return "Usuário cadastrado com sucesso!";
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            return "Email já cadastrado!";
        }
        return "Erro ao cadastrar: " . $e->getMessage();
    }
}

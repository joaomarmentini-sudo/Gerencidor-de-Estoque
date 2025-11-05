<?php
session_start();

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Preencha todos os campos!";
        header("Location: ../view/login.php");
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // login ok
            $_SESSION['usuario'] = [
                'id' => $usuario['id_usuario'],
                'nome' => $usuario['nome'],
                'tipo' => $usuario['tipo']
            ];
            header("Location: ../dashboard.php");
            exit;
        } else {
            $_SESSION['erro'] = "Email ou senha incorretos!";
            header("Location: ../view/login.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['erro'] = "Erro ao acessar o banco de dados.";
        header("Location: ../view/login.php");
        exit;
    }
} else {
    header("Location: ../view/login.php");
    exit;
}
?>

<?php
require_once __DIR__ . '/../../controller/usuarioController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensagem = cadastrarUsuario($_POST['nome'], $_POST['email'], $_POST['senha'], $_POST['tipo']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3 class="text-center mb-4">Cadastrar Usuário</h3>
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info"><?= $mensagem ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Senha</label>
                <input type="password" name="senha" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Tipo de Usuário</label>
                <select name="tipo" class="form-select">
                    <option value="funcionario">Funcionário</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button class="btn btn-primary w-100">Cadastrar</button>
            <a href="../login.php" class="btn btn-link w-100 mt-2">Voltar ao login</a>
        </form>
    </div>
</div>
</body>
</html>


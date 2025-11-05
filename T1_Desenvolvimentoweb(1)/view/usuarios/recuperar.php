<?php
require '../../config.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $novaSenha = substr(md5(time()), 0, 8);
    $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE email = ?");
    $stmt->execute([$senhaHash, $email]);

    if ($stmt->rowCount() > 0) {
        $mensagem = "Nova senha gerada: <strong>$novaSenha</strong><br>Use-a para fazer login.";
    } else {
        $mensagem = "Email não encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card p-4 shadow">
        <h3 class="text-center mb-4">Recuperar Senha</h3>
        <?php if (!empty($mensagem)): ?>
            <div class="alert alert-info"><?= $mensagem ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label>Email cadastrado</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <button class="btn btn-primary w-100">Gerar nova senha</button>
            <a href="../login.php" class="btn btn-link w-100 mt-2">Voltar ao login</a>
        </form>
    </div>
</div>
</body>
</html>

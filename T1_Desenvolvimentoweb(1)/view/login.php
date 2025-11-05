<?php session_start(); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gerenciador de Estoque</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Estoque</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card-login {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .logo {
            width: 60px;
            height: auto;
            margin-right: 15px;
        }

        .brand-header {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 20px;
        }

        .brand-header h4 {
            color: #343a40;
            font-weight: 600;
        }

        .btn-login {
            background-color: #007bff;
            border: none;
        }

        .btn-login:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-login p-4">
                <div class="card-body">

                    <!-- Logo e título -->
                    <div class="brand-header">
                        <img src="../arena_logo.png" alt="Logo da Loja" class="logo">
                        <h4>Sistema de Estoque</h4>
                    </div>

                    <!-- Mensagem de erro -->
                    <?php if(isset($_SESSION['erro'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= $_SESSION['erro']; unset($_SESSION['erro']); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Formulário -->
                    <form action="../controller/loginController.php" method="POST">
                        <div class="mb-3">
                            <label>Email:</label>
                            <input type="email" name="email" class="form-control" placeholder="Digite seu email" required>
                        </div>
                        <div class="mb-3">
                            <label>Senha:</label>
                            <input type="password" name="senha" class="form-control" placeholder="Digite sua senha" required>
                        </div>
                        <button type="submit" class="btn btn-login w-100">Entrar</button>
                        <a href="usuarios/recuperar.php" class="btn btn-link mt-2">Esqueci minha senha</a>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


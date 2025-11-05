<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: view/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Painel - Gerenciador de Estoque</title>
<!-- Bootstrap 5.3 CSS via CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="..." crossorigin="anonymous">
<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2JDjG4r9S9DmPpY7RlLUPaZ/2ftA3VhZzkgP1m9c7F2u7i4F2hP2oCj2jF0" crossorigin="anonymous"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Bem-vindo, <?= htmlspecialchars($usuario['nome']); ?>!</h3>
    <p>Você está logado como <strong><?= $usuario['tipo']; ?></strong>.</p>
    <a href="controller/logout.php" class="btn btn-danger mb-3">Sair</a>

    <!-- Abas do dashboard -->
    <ul class="nav nav-tabs" id="dashboardTabs" role="tablist">
        <?php if($usuario['tipo'] === 'admin'): ?>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="criar-tab" href="view/produtos/criar.php">Criar Produto</a>
            </li>
        <?php endif; ?>
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="listar-tab" href="view/produtos/listar.php">Listar Produtos</a>
        </li>
        <li class="nav-item" role="presentation">
            <a href="view/produtos/relatorio.php" class="nav-link">Gerar Relatório Excel</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <div class="tab-pane fade show active" id="conteudo" role="tabpanel">
            <p>Escolha uma das abas acima para gerenciar os produtos.</p>
        </div>
    </div>
</div>

</body>
</html>


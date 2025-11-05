<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// Buscar produtos com estoque total
$stmt = $pdo->query("
    SELECT p.*, SUM(e.quantidade) AS total_estoque
    FROM produtos p
    LEFT JOIN estoque_tamanhos e ON p.id_produto = e.id_produto
    GROUP BY p.id_produto
    ORDER BY p.nome
");
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Listar Produtos - Gerenciador de Estoque</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Produtos</h3>
    <?php if($usuario['tipo'] === 'admin'): ?>
        <a href="criar.php" class="btn btn-success mb-3">Novo Produto</a>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Preço</th>
                <th>Total Estoque</th>
                <?php if($usuario['tipo'] === 'admin'): ?>
                    <th>Ações</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach($produtos as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nome']) ?></td>
                    <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
                    <td><?= $p['total_estoque'] ?? 0 ?></td>
                    <?php if($usuario['tipo'] === 'admin'): ?>
                        <td>
                            <a href="editar.php?id=<?= $p['id_produto'] ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="../../controller/produtoController.php?acao=excluir&id=<?= $p['id_produto'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Deseja realmente excluir?')">Excluir</a>
                            <a href="detalhes.php?id=<?= $p['id_produto'] ?>" class="btn btn-info btn-sm">Detalhes</a>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="../../dashboard.php" class="btn btn-secondary w-100 mt-2">Voltar</a>
</div>

</body>
</html>

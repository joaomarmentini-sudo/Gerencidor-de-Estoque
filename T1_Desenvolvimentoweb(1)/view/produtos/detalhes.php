<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listar.php");
    exit;
}

// Busca dados do produto
$stmt = $pdo->prepare("SELECT p.*, c.nome AS categoria, f.nome AS fornecedor
                       FROM produtos p
                       LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
                       LEFT JOIN fornecedores f ON p.id_fornecedor = f.id_fornecedor
                       WHERE id_produto = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

// Busca estoque por tamanho
$stmt2 = $pdo->prepare("SELECT tamanho, quantidade FROM estoque_tamanhos WHERE id_produto = ? ORDER BY tamanho");
$stmt2->execute([$id]);
$estoque = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR);

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Detalhes do Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Detalhes do Produto</h3>
    <a href="listar.php" class="btn btn-secondary mb-3">Voltar</a>

    <div class="row">
        <div class="col-md-4">
            <?php if(!empty($produto['imagem']) && file_exists('../../uploads/'.$produto['imagem'])): ?>
                <img src="../../uploads/<?= $produto['imagem'] ?>" class="img-fluid rounded" alt="Foto do Produto">
            <?php else: ?>
                <img src="../../uploads/placeholder.png" class="img-fluid rounded" alt="Sem imagem">
            <?php endif; ?>
        </div>
        <div class="col-md-8">
            <p><strong>Nome:</strong> <?= htmlspecialchars($produto['nome']) ?></p>
            <p><strong>Descrição:</strong> <?= htmlspecialchars($produto['descricao']) ?></p>
            <p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
            <p><strong>Categoria:</strong> <?= htmlspecialchars($produto['categoria']) ?></p>
            <p><strong>Fornecedor:</strong> <?= htmlspecialchars($produto['fornecedor']) ?></p>

            <h5>Estoque por Tamanho</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <?php for($i=33; $i<=46; $i++): ?>
                            <th><?= $i ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <?php for($i=33; $i<=46; $i++): ?>
                            <td><?= $estoque[$i] ?? 0 ?></td>
                        <?php endfor; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>

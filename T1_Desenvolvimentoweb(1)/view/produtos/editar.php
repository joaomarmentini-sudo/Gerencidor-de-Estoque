<?php
session_start();
require_once '../../config.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

// Pega id do produto
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: listar.php");
    exit;
}

// Busca dados do produto
$stmt = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
$stmt->execute([$id]);
$produto = $stmt->fetch();

// Busca estoque por tamanho
$stmt2 = $pdo->prepare("SELECT tamanho, quantidade FROM estoque_tamanhos WHERE id_produto = ?");
$stmt2->execute([$id]);
$estoque = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR);

// Buscar categorias e fornecedores
$categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome")->fetchAll();
$fornecedores = $pdo->query("SELECT * FROM fornecedores ORDER BY nome")->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Produto</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h3>Editar Produto</h3>
    <form action="../../controller/produtoController.php?acao=editar" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">

        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($produto['nome']) ?>" required>
        </div>

        <div class="mb-3">
            <label>Descrição:</label>
            <textarea name="descricao" class="form-control"><?= htmlspecialchars($produto['descricao']) ?></textarea>
        </div>

        <div class="mb-3">
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" class="form-control" value="<?= $produto['preco'] ?>" required>
        </div>

        <div class="mb-3">
            <label>Categoria:</label>
            <select name="id_categoria" class="form-select">
                <option value="">Selecione</option>
                <?php foreach($categorias as $c): ?>
                    <option value="<?= $c['id_categoria'] ?>" <?= $c['id_categoria'] == $produto['id_categoria'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Fornecedor:</label>
            <select name="id_fornecedor" class="form-select">
                <option value="">Selecione</option>
                <?php foreach($fornecedores as $f): ?>
                    <option value="<?= $f['id_fornecedor'] ?>" <?= $f['id_fornecedor'] == $produto['id_fornecedor'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($f['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label>Imagem do Produto:</label>
            <input type="file" name="imagem" class="form-control" accept="image/*">
            <?php if(!empty($produto['imagem']) && file_exists('../../uploads/'.$produto['imagem'])): ?>
                <img src="../../uploads/<?= $produto['imagem'] ?>" class="img-thumbnail mt-2" style="max-width:150px;">
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label>Quantidade por tamanho:</label>
            <div class="row">
                <?php for($i=33; $i<=46; $i++): ?>
                    <div class="col-1 text-center">
                        <label><?= $i ?></label>
                        <input type="number" name="tamanho[<?= $i ?>]" value="<?= $estoque[$i] ?? 0 ?>" min="0" class="form-control">
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Salvar Alterações</button>
        <a href="listar.php" class="btn btn-secondary mt-3">Voltar</a>
    </form>
</div>

</body>
</html>

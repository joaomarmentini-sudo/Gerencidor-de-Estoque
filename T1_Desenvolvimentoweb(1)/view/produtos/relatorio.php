<?php
require '../../config.php';

// Cabeçalhos para download do Excel
header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=relatorio_produtos.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Buscar produtos
$stmt = $pdo->query("
    SELECT p.id_produto, p.nome, p.preco, c.nome AS categoria, f.nome AS fornecedor
    FROM produtos p
    LEFT JOIN categorias c ON p.id_categoria = c.id_categoria
    LEFT JOIN fornecedores f ON p.id_fornecedor = f.id_fornecedor
    ORDER BY p.nome
");
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Cabeçalho da tabela
echo "<table border='1'>";
echo "<tr style='background-color:#f2f2f2;'>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Categoria</th>
        <th>Fornecedor</th>";

for ($i = 33; $i <= 46; $i++) {
    echo "<th>T$i</th>";
}

echo "<th>Total Estoque</th>";
echo "</tr>";

// Dados dos produtos
foreach($produtos as $p){
    // Estoque por tamanho
    $stmt2 = $pdo->prepare("SELECT tamanho, quantidade FROM estoque_tamanhos WHERE id_produto = ?");
    $stmt2->execute([$p['id_produto']]);
    $estoque = $stmt2->fetchAll(PDO::FETCH_KEY_PAIR);

    $total = 0;
    echo "<tr>";
    echo "<td>{$p['id_produto']}</td>";
    echo "<td>" . utf8_decode($p['nome']) . "</td>";
    echo "<td>" . utf8_decode($p['preco']) . "</td>";
    echo "<td>" . utf8_decode($p['categoria']) . "</td>";
    echo "<td>" . utf8_decode($p['fornecedor']) . "</td>";

    for($i=33; $i<=46; $i++){
        $q = $estoque[$i] ?? 0;
        echo "<td>$q</td>";
        $total += $q;
    }

    echo "<td>$total</td>";
    echo "</tr>";
}

echo "</table>";
exit;



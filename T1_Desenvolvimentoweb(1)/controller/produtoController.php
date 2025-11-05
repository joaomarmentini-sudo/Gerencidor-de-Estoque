<?php
session_start();
require_once '../config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$acao = $_GET['acao'] ?? '';

// Apenas admin pode criar, editar ou excluir
if (in_array($acao, ['criar', 'editar', 'excluir']) && $usuario['tipo'] !== 'admin') {
    echo "<h3>Acesso negado: você não tem permissão para esta ação.</h3>";
    exit;
}

switch($acao) {

    // ===========================
    case 'criar':
        // Upload da imagem
        $nome_imagem = null;
        if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nome_imagem = uniqid() . "." . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/../uploads/' . $nome_imagem);
        }

        // Inserir produto
        $stmt = $pdo->prepare("INSERT INTO produtos 
            (nome, descricao, preco, id_categoria, id_fornecedor, imagem)
            VALUES (:nome, :descricao, :preco, :id_categoria, :id_fornecedor, :imagem)");
        $stmt->execute([
            ':nome' => $_POST['nome'],
            ':descricao' => $_POST['descricao'],
            ':preco' => $_POST['preco'],
            ':id_categoria' => !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null,
            ':id_fornecedor' => !empty($_POST['id_fornecedor']) ? $_POST['id_fornecedor'] : null,
            ':imagem' => $nome_imagem
        ]);

        $id_produto = $pdo->lastInsertId();

        // Inserir estoque por tamanho
        foreach($_POST['tamanho'] as $t => $q){
            if($q > 0){
                $stmt2 = $pdo->prepare("INSERT INTO estoque_tamanhos (id_produto, tamanho, quantidade)
                                        VALUES (:id_produto, :tamanho, :quantidade)");
                $stmt2->execute([
                    ':id_produto' => $id_produto,
                    ':tamanho' => $t,
                    ':quantidade' => $q
                ]);
            }
        }

        header("Location: ../view/produtos/listar.php");
        break;

    // ===========================
    case 'editar':
        $id = $_POST['id_produto'];

        // Upload de nova imagem
        $nome_imagem = null;
        if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $nome_imagem = uniqid() . "." . $ext;
            move_uploaded_file($_FILES['imagem']['tmp_name'], __DIR__ . '/../uploads/' . $nome_imagem);
        }

        // Atualiza dados do produto
        if($nome_imagem){
            $stmt = $pdo->prepare("UPDATE produtos SET 
                nome = :nome, descricao = :descricao, preco = :preco, 
                id_categoria = :id_categoria, id_fornecedor = :id_fornecedor, imagem = :imagem
                WHERE id_produto = :id_produto");
            $stmt->execute([
                ':nome' => $_POST['nome'],
                ':descricao' => $_POST['descricao'],
                ':preco' => $_POST['preco'],
                ':id_categoria' => !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null,
                ':id_fornecedor' => !empty($_POST['id_fornecedor']) ? $_POST['id_fornecedor'] : null,
                ':imagem' => $nome_imagem,
                ':id_produto' => $id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE produtos SET 
                nome = :nome, descricao = :descricao, preco = :preco, 
                id_categoria = :id_categoria, id_fornecedor = :id_fornecedor
                WHERE id_produto = :id_produto");
            $stmt->execute([
                ':nome' => $_POST['nome'],
                ':descricao' => $_POST['descricao'],
                ':preco' => $_POST['preco'],
                ':id_categoria' => !empty($_POST['id_categoria']) ? $_POST['id_categoria'] : null,
                ':id_fornecedor' => !empty($_POST['id_fornecedor']) ? $_POST['id_fornecedor'] : null,
                ':id_produto' => $id
            ]);
        }

        // Atualiza estoque: remove todos os registros antigos
        $pdo->prepare("DELETE FROM estoque_tamanhos WHERE id_produto = ?")->execute([$id]);

        // Insere estoque atualizado
        foreach($_POST['tamanho'] as $t => $q){
            if($q > 0){
                $stmt2 = $pdo->prepare("INSERT INTO estoque_tamanhos (id_produto, tamanho, quantidade)
                                        VALUES (:id_produto, :tamanho, :quantidade)");
                $stmt2->execute([
                    ':id_produto' => $id,
                    ':tamanho' => $t,
                    ':quantidade' => $q
                ]);
            }
        }

        header("Location: ../view/produtos/listar.php");
        break;

    // ===========================
    case 'excluir':
        $id = $_GET['id'];

        // Exclui imagem do servidor, se existir
        $stmtImg = $pdo->prepare("SELECT imagem FROM produtos WHERE id_produto = ?");
        $stmtImg->execute([$id]);
        $img = $stmtImg->fetchColumn();
        if($img && file_exists(__DIR__ . '/../uploads/' . $img)){
            unlink(__DIR__ . '/../uploads/' . $img);
        }

        // Excluir produto (estoque relacionado será excluído automaticamente)
        $stmt = $pdo->prepare("DELETE FROM produtos WHERE id_produto = ?");
        $stmt->execute([$id]);

        header("Location: ../view/produtos/listar.php");
        break;

    default:
        echo "<h3>Ação inválida</h3>";
        break;
}
?>


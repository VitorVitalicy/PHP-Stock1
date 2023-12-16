<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

include 'conexao.php';

// Verifica se o carrinho não está vazio
if (!empty($_SESSION['carrinho'])) {
    // Inicia a transação
    mysqli_autocommit($conexao, false);

    try {
        $id_usuario = $_SESSION['id_usuario'];

        // Insira o código aqui para registrar a compra no banco de dados
        // ...

        // Limpa o carrinho
        unset($_SESSION['carrinho']);

        // Confirma a transação
        mysqli_commit($conexao);

        // Redireciona para a lista de produtos
        header('Location: lista_produtos.php');
    } catch (Exception $e) {
        // Se ocorrer um erro, desfaz a transação
        mysqli_rollback($conexao);
        echo "Erro na transação: " . $e->getMessage();
    }
} else {
    // Se o carrinho estiver vazio, redireciona para a lista de produtos
    header('Location: lista_produtos.php');
}
?>

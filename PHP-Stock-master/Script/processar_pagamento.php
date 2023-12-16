<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

include 'conexao.php';

$usuario = $_SESSION['usuario'];

$sqlUsuario = "SELECT `id_usuario` FROM `usuarios` WHERE `mail_usuario` = ? and `status` = 'Ativo'";
$stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
mysqli_stmt_bind_param($stmtUsuario, "s", $usuario);
mysqli_stmt_execute($stmtUsuario);
$resultUsuario = mysqli_stmt_get_result($stmtUsuario);
$rowUsuario = mysqli_fetch_array($resultUsuario);
$idUsuario = $rowUsuario['id_usuario'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $total = isset($_POST['total']) ? $_POST['total'] : 0;
    $formaPagamento = isset($_POST['forma_pagamento']) ? $_POST['forma_pagamento'] : '';

    // Insira os dados do pagamento no banco de dados
    $sqlPagamento = "INSERT INTO pagamentos (id_usuario, total, forma_pagamento) VALUES (?, ?, ?)";
    $stmtPagamento = mysqli_prepare($conexao, $sqlPagamento);
    mysqli_stmt_bind_param($stmtPagamento, "ids", $idUsuario, $total, $formaPagamento);
    mysqli_stmt_execute($stmtPagamento);

    // Exiba a página de controle financeiro
    header('Location: controle_financeiro.php');
    exit();
} else {
    header('Location: carrinho.php');
    exit();
}
?>

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

$sqlLimparCarrinho = "DELETE FROM carrinho WHERE id_usuario = ?";
$stmtLimparCarrinho = mysqli_prepare($conexao, $sqlLimparCarrinho);
mysqli_stmt_bind_param($stmtLimparCarrinho, "i", $idUsuario);
mysqli_stmt_execute($stmtLimparCarrinho);

header('Location: carrinho.php'); // Redireciona de volta para a página do carrinho
exit();
?>

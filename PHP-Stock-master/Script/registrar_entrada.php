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

$valorEntrada = $_POST['valor'];

$sqlRegistrarEntrada = "INSERT INTO movimentacao (id_usuario, tipo, valor) VALUES (?, 'entrada', ?)";
$stmtRegistrarEntrada = mysqli_prepare($conexao, $sqlRegistrarEntrada);
mysqli_stmt_bind_param($stmtRegistrarEntrada, "id", $idUsuario, $valorEntrada);
$result = mysqli_stmt_execute($stmtRegistrarEntrada);

if ($result) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => mysqli_error($conexao)]);
}

exit();
?>

<?php
// registrar_movimentacao.php

// Inicializa a sessão (se ainda não estiver inicializada)
session_start();

// Verifica se o usuário está autenticado (ajuste conforme necessário)
if (!isset($_SESSION['usuario'])) {
    // Se não estiver autenticado, retorna um erro
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit();
}

// Recupera os dados do POST
$data = json_decode(file_get_contents("php://input"));

// Verifica se os dados necessários foram recebidos
if (!isset($data->tipo) || !isset($data->valor) || !isset($data->formaPagamento)) {
    echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    exit();
}

// Conecta ao banco de dados (ajuste conforme necessário)
include 'conexao.php';

// Substitua pelo método adequado para obter o ID do usuário (ajuste conforme necessário)
$idUsuario = 1;

// Prepara a consulta para inserir os dados na tabela movimentacao
$sql = "INSERT INTO movimentacao (id_usuario, tipo, valor, forma_pagamento, data_movimentacao) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP)";
$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "isds", $idUsuario, $data->tipo, $data->valor, $data->formaPagamento);

// Executa a consulta
if (mysqli_stmt_execute($stmt)) {
    // Se a inserção foi bem-sucedida, retorna sucesso
    echo json_encode(['success' => true]);
} else {
    // Se houve um erro na inserção, retorna o erro
    echo json_encode(['success' => false, 'error' => mysqli_error($conexao)]);
}

// Fecha a conexão e a declaração
mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>

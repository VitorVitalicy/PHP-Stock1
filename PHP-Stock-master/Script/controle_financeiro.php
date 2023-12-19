<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

include 'conexao.php';

$usuario = $_SESSION['usuario'];

$sqlNivel = "SELECT `id_usuario`, `nivel_usuario` FROM `usuarios` WHERE `mail_usuario` = ? AND `status` = 'Ativo'";
$stmtNivel = mysqli_prepare($conexao, $sqlNivel);
mysqli_stmt_bind_param($stmtNivel, "s", $usuario);
mysqli_stmt_execute($stmtNivel);
$resultNivel = mysqli_stmt_get_result($stmtNivel);
$rowNivel = mysqli_fetch_assoc($resultNivel);
$idUsuario = $rowNivel['id_usuario'];
$nivel = $rowNivel['nivel_usuario'];

if ($nivel != 1 && $nivel != 2) {
    echo "Você não tem permissão para acessar esta página.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tipo = $_POST['tipo'];
    $valor = $_POST['valor'];
    $forma_pagamento = $_POST['forma_pagamento'];

    $sqlInsert = "INSERT INTO `movimentacao` (`id_usuario`, `tipo`, `valor`, `forma_pagamento`, `descricao`, `data_movimentacao`) 
    VALUES (?, ?, ?, ?, ?, NOW())";

    $stmtInsert = mysqli_prepare($conexao, $sqlInsert);
    mysqli_stmt_bind_param($stmtInsert, "ssdss", $idUsuario, $tipo, $valor, $forma_pagamento, $descricao);



    if (mysqli_stmt_execute($stmtInsert)) {
        header('Location: ' . $_SERVER['PHP_SELF']);
    } else {
        echo "Erro ao inserir na tabela de movimentação: " . mysqli_error($conexao);
    }
}

$mesAtual = date('m');
$anoAtual = date('Y');

$sqlEntradas = "SELECT SUM(valor) AS total_entradas FROM movimentacao WHERE id_usuario = ? AND tipo = 'entrada' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
$sqlSaidas = "SELECT SUM(valor) AS total_saidas FROM movimentacao WHERE id_usuario = ? AND tipo = 'saida' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";

$stmtEntradas = mysqli_prepare($conexao, $sqlEntradas);
$stmtSaidas = mysqli_prepare($conexao, $sqlSaidas);

mysqli_stmt_bind_param($stmtEntradas, "isi", $idUsuario, $mesSelecionado, $anoAtual);
mysqli_stmt_bind_param($stmtSaidas, "isi", $idUsuario, $mesSelecionado, $anoAtual);

mysqli_stmt_execute($stmtEntradas);
$resultEntradas = mysqli_stmt_get_result($stmtEntradas);
$rowEntradas = mysqli_fetch_assoc($resultEntradas);
$totalEntradas = $rowEntradas['total_entradas'] ?: 0;

mysqli_stmt_execute($stmtSaidas);
$resultSaidas = mysqli_stmt_get_result($stmtSaidas);
$rowSaidas = mysqli_fetch_assoc($resultSaidas);
$totalSaidas = $rowSaidas['total_saidas'] ?: 0;


// Obtém o valor total de entradas e saídas do mês anterior
$mesAnterior = date('m', strtotime('-1 month'));

$sqlEntradasAnterior = "SELECT SUM(valor) AS total_entradas FROM movimentacao WHERE id_usuario = ? AND tipo = 'entrada' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
$sqlSaidasAnterior = "SELECT SUM(valor) AS total_saidas FROM movimentacao WHERE id_usuario = ? AND tipo = 'saida' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";

$stmtEntradasAnterior = mysqli_prepare($conexao, $sqlEntradasAnterior);
$stmtSaidasAnterior = mysqli_prepare($conexao, $sqlSaidasAnterior);

mysqli_stmt_bind_param($stmtEntradasAnterior, "isi", $idUsuario, $mesAnterior, $anoAtual);
mysqli_stmt_bind_param($stmtSaidasAnterior, "isi", $idUsuario, $mesAnterior, $anoAtual);

mysqli_stmt_execute($stmtEntradasAnterior);
$resultEntradasAnterior = mysqli_stmt_get_result($stmtEntradasAnterior);
$rowEntradasAnterior = mysqli_fetch_assoc($resultEntradasAnterior);
$totalEntradasAnterior = $rowEntradasAnterior['total_entradas'] ?: 0;

mysqli_stmt_execute($stmtSaidasAnterior);
$resultSaidasAnterior = mysqli_stmt_get_result($stmtSaidasAnterior);
$rowSaidasAnterior = mysqli_fetch_assoc($resultSaidasAnterior);
$totalSaidasAnterior = $rowSaidasAnterior['total_saidas'] ?: 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimentação</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://kit.fontawesome.com/cae6919cdb.js" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <div class="container" style="margin-top: 25px;">
        <div class="row">
            <div class="col-sm-6">
                <!-- Formulário de registro de movimentação -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="tipo">Tipo de Movimentação:</label>
                        <select class="form-control" id="tipo" name="tipo" required>
                            <option value="entrada">Entrada</option>
                            <option value="saida">Saída</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor:</label>
                        <input type="number" class="form-control" id="valor" name="valor" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="forma_pagamento">Forma de Pagamento:</label>
                        <select class="form-control" id="forma_pagamento" name="forma_pagamento" required>
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                            <option value="pix">PIX</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                    </div>
                    <div class="form-group">
                    <label for="descricao">Descrição (Opcional):</label>
                    <input type="text" class="form-control" id="descricao" name="descricao">
                    </div>
                    <button type="submit" class="btn btn-primary">Registrar Movimentação</button>
                </form>
            </div>

            <!-- Blocos de exibição do total de entradas e saídas do mês -->
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Resumo do Mês</h5>
                        <p>Total de Entradas: <span style="color: green;">R$ <?php echo number_format($totalEntradas, 2, ',', '.'); ?></span></p>
                        <p>Total de Saídas: <span style="color: red;">R$ <?php echo number_format($totalSaidas, 2, ',', '.'); ?></span></p>
                    </div>
                </div>
<!-- Menu suspenso para exibir registros do mês atual -->
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <!-- Bloco de Entradas -->
            <div class="col-md-6">
                <div class="entrada">
                    <h5 style="color: green; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Entradas</h5>
                    <?php
                    $sqlRegistrosMesAtualEntrada = "SELECT * FROM movimentacao WHERE id_usuario = ? AND tipo = 'entrada' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
                    $stmtRegistrosMesAtualEntrada = mysqli_prepare($conexao, $sqlRegistrosMesAtualEntrada);
                    mysqli_stmt_bind_param($stmtRegistrosMesAtualEntrada, "isi", $idUsuario, $mesAtual, $anoAtual);
                    mysqli_stmt_execute($stmtRegistrosMesAtualEntrada);
                    $resultRegistrosMesAtualEntrada = mysqli_stmt_get_result($stmtRegistrosMesAtualEntrada);

                    while ($row = mysqli_fetch_assoc($resultRegistrosMesAtualEntrada)) {
                        echo "<p style='color: green;'>Tipo: " . $row['tipo'] . " | Valor: R$ " . number_format($row['valor'], 2, ',', '.') . " | Forma de Pagamento: " . $row['forma_pagamento'] . "</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Bloco de Saídas -->
            <div class="col-md-6">
                <div class="saida">
                    <h5 style="color: red; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Saídas</h5>
                    <?php
                    $sqlRegistrosMesAtualSaida = "SELECT * FROM movimentacao WHERE id_usuario = ? AND tipo = 'saida' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
                    $stmtRegistrosMesAtualSaida = mysqli_prepare($conexao, $sqlRegistrosMesAtualSaida);
                    mysqli_stmt_bind_param($stmtRegistrosMesAtualSaida, "isi", $idUsuario, $mesAtual, $anoAtual);
                    mysqli_stmt_execute($stmtRegistrosMesAtualSaida);
                    $resultRegistrosMesAtualSaida = mysqli_stmt_get_result($stmtRegistrosMesAtualSaida);

                    while ($row = mysqli_fetch_assoc($resultRegistrosMesAtualSaida)) {
                        echo "<p style='color: red;'>Tipo: " . $row['tipo'] . " | Valor: R$ " . number_format($row['valor'], 2, ',', '.') . " | Forma de Pagamento: " . $row['forma_pagamento'] . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Menu suspenso para exibir registros do mês anterior -->
<div class="card mt-3">
    <div class="card-body">
        <div class="row">
            <!-- Bloco de Entradas -->
            <div class="col-md-6">
                <div class="entrada">
                    <h5 style="color: green; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Entradas</h5>
                    <?php
                    $sqlRegistrosMesAnteriorEntrada = "SELECT * FROM movimentacao WHERE id_usuario = ? AND tipo = 'entrada' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
                    $stmtRegistrosMesAnteriorEntrada = mysqli_prepare($conexao, $sqlRegistrosMesAnteriorEntrada);
                    mysqli_stmt_bind_param($stmtRegistrosMesAnteriorEntrada, "isi", $idUsuario, $mesAnterior, $anoAtual);
                    mysqli_stmt_execute($stmtRegistrosMesAnteriorEntrada);
                    $resultRegistrosMesAnteriorEntrada = mysqli_stmt_get_result($stmtRegistrosMesAnteriorEntrada);

                    while ($row = mysqli_fetch_assoc($resultRegistrosMesAnteriorEntrada)) {
                        echo "<p style='color: green;'>Tipo: " . $row['tipo'] . " | Valor: R$ " . number_format($row['valor'], 2, ',', '.') . " | Forma de Pagamento: " . $row['forma_pagamento'] . "</p>";
                    }
                    ?>
                </div>
            </div>

            <!-- Bloco de Saídas -->
            <div class="col-md-6">
                <div class="saida">
                    <h5 style="color: red; border-bottom: 1px solid #ddd; padding-bottom: 5px;">Saídas</h5>
                    <?php
                    $sqlRegistrosMesAnteriorSaida = "SELECT * FROM movimentacao WHERE id_usuario = ? AND tipo = 'saida' AND MONTH(data_movimentacao) = ? AND YEAR(data_movimentacao) = ?";
                    $stmtRegistrosMesAnteriorSaida = mysqli_prepare($conexao, $sqlRegistrosMesAnteriorSaida);
                    mysqli_stmt_bind_param($stmtRegistrosMesAnteriorSaida, "isi", $idUsuario, $mesAnterior, $anoAtual);
                    mysqli_stmt_execute($stmtRegistrosMesAnteriorSaida);
                    $resultRegistrosMesAnteriorSaida = mysqli_stmt_get_result($stmtRegistrosMesAnteriorSaida);

                    while ($row = mysqli_fetch_assoc($resultRegistrosMesAnteriorSaida)) {
                        echo "<p style='color: red;'>Tipo: " . $row['tipo'] . " | Valor: R$ " . number_format($row['valor'], 2, ',', '.') . " | Forma de Pagamento: " . $row['forma_pagamento'] . "</p>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>

    <script src="https://kit.fontawesome.com/cae6919cdb.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>

</html>

<?php
// controle_financeiro.php

// Inclua o arquivo de conexão
include 'conexao.php';

// Substitua pelo método adequado para obter o ID do usuário
session_start();
$usuario = $_SESSION['usuario'];

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
}

$sqlUsuario = "SELECT `nivel_usuario`, `id_usuario` FROM `usuarios` WHERE `mail_usuario` = ? and `status` = 'Ativo'";
$stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
mysqli_stmt_bind_param($stmtUsuario, "s", $usuario);
mysqli_stmt_execute($stmtUsuario);
$resultUsuario = mysqli_stmt_get_result($stmtUsuario);
$rowUsuario = mysqli_fetch_array($resultUsuario);
$nivel = $rowUsuario['nivel_usuario'];
$idUsuario = $rowUsuario['id_usuario'];

// Consulta para obter os dados financeiros
$sqlFinanceiro = "SELECT * FROM movimentacao WHERE id_usuario = ? ORDER BY data_movimentacao DESC";
$stmtFinanceiro = mysqli_prepare($conexao, $sqlFinanceiro);
mysqli_stmt_bind_param($stmtFinanceiro, "i", $idUsuario);
mysqli_stmt_execute($stmtFinanceiro);
$resultFinanceiro = mysqli_stmt_get_result($stmtFinanceiro);

// Feche a conexão
mysqli_stmt_close($stmtUsuario);
mysqli_stmt_close($stmtFinanceiro);
mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Controle Financeiro</title>

    <!-- Adicione o link para a folha de estilo CSS aqui -->
    <link rel="stylesheet" href="styles.css">

    <!-- Os links abaixo são exemplos baseados no seu arquivo menu.php -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://kit.fontawesome.com/cae6919cdb.js">
</head>

<body>
    <!-- O conteúdo da sua página aqui -->
    <div class="container" style="margin-top: 40px">
        <center>
            <h3>Controle Financeiro</h3>
        </center>
        <br>
        <br>

        <!-- Bloco de Entrada de Caixa -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Entrada de Caixa</h5>
                        <!-- Adicione um campo de entrada para o valor de entrada -->
                        <input type="text" id="entradaValor" placeholder="Valor de Entrada">
                        <!-- Adicione um menu suspenso para a forma de pagamento -->
                        <select id="formaPagamentoEntrada">
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                            <option value="pix">PIX</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                        <button class="btn btn-success" onclick="registrarMovimentacao('entrada')">Registrar Entrada</button>
                        <p id="valorEntrada">Valor: R$ 0.00</p>
                    </div>
                </div>
            </div>

            <!-- Bloco de Saída de Caixa -->
            <div class="col-md-6">
                <div class="card" style="border-color: red;">
                    <div class="card-body">
                        <h5 class="card-title">Saída de Caixa</h5>
                        <!-- Adicione um campo de entrada para o valor de saída -->
                        <input type="text" id="saidaValor" placeholder="Valor de Saída">
                        <!-- Adicione um menu suspenso para a forma de pagamento -->
                        <select id="formaPagamentoSaida">
                            <option value="debito">Débito</option>
                            <option value="credito">Crédito</option>
                            <option value="pix">PIX</option>
                            <option value="dinheiro">Dinheiro</option>
                        </select>
                        <button class="btn btn-danger" onclick="registrarMovimentacao('saida')">Registrar Saída</button>
                        <p id="valorSaida">Valor: R$ 0.00</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Produtos Mais Vendidos -->
        <!-- Restante do conteúdo permanece o mesmo -->

        <!-- Botão para Voltar para o Menu -->
        <div class="fixed-bottom" style="margin-bottom: 20px; margin-right: 20px;">
            <a href="menu.php" class="btn btn-primary btn-sm float-right">Voltar para o Menu</a>
        </div>

        <!-- Exibição dos Dados Financeiros -->
        <?php
        if ($resultFinanceiro) {
            while ($rowFinanceiro = mysqli_fetch_assoc($resultFinanceiro)) {
                $totalPago = $rowFinanceiro['valor'];
                $formaPagamento = $rowFinanceiro['forma_pagamento'];

                echo "<p>Total Pago: R$ $totalPago</p>";
                echo "<p>Forma de Pagamento: $formaPagamento</p>";
                echo "<hr>";
            }
        } else {
            echo "<p>Nenhum dado financeiro encontrado.</p>";
        }
        ?>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>

    <script>
        function registrarMovimentacao(tipo) {
            var valorElement, valorCampo, formaPagamentoElement;

            if (tipo === 'entrada') {
                valorElement = document.getElementById('valorEntrada');
                valorCampo = document.getElementById('entradaValor');
                formaPagamentoElement = document.getElementById('formaPagamentoEntrada');
            } else {
                valorElement = document.getElementById('valorSaida');
                valorCampo = document.getElementById('saidaValor');
                formaPagamentoElement = document.getElementById('formaPagamentoSaida');
            }

            var valor = parseFloat(valorCampo.value);
            var formaPagamento = formaPagamentoElement.value;

            if (isNaN(valor)) {
                alert(`Por favor, insira um valor numérico válido para a ${tipo}.`);
                return;
            }

            var novoValor = parseFloat(valorElement.innerText.split(' ')[1]) + valor;
            valorElement.innerHTML = `Valor: R$ ${novoValor.toFixed(2)}`;

            // AJAX utilizando a função fetch
            fetch('registrar_movimentacao.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ tipo: tipo, valor: valor, formaPagamento: formaPagamento }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualiza a página após o registro (remova isso se não for necessário)
                    location.reload();
                } else {
                    console.error(`Erro ao registrar ${tipo}:`, data.error);
                }
            })
            .catch(error => {
                console.error(`Erro ao registrar ${tipo}:`, error);
            });
        }
    </script>

</body>

</html>

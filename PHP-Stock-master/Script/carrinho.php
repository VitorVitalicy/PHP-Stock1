<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>
</head>
<body>

    <div class="container" style="margin-top: 40px">
        <center>
            <h3>Carrinho</h3>
        </center>
        <br>
        <br>

        <table class="table" id="table_id">
            <thead>
                <tr>
                    <th scope="col">Nome do produto</th>
                    <th scope="col">Preço unitário</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Ações</th>
                </tr>
            </thead>
            <?php

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

$sqlCarrinho = "SELECT carrinho.*, produtos.nome_produto, produtos.preco_produto FROM carrinho
                INNER JOIN produtos ON carrinho.id_produto = produtos.id_produto
                WHERE carrinho.id_usuario = ?";
$stmtCarrinho = mysqli_prepare($conexao, $sqlCarrinho);
mysqli_stmt_bind_param($stmtCarrinho, "i", $idUsuario);
mysqli_stmt_execute($stmtCarrinho);
$resultCarrinho = mysqli_stmt_get_result($stmtCarrinho);

while ($array = mysqli_fetch_array($resultCarrinho)) {
    $idCarrinho = $array['id_carrinho'];
    $nomeProduto = $array['nome_produto'];
    $precoUnitario = $array['preco_produto'];
    $quantidade = $array['quantidade'];
    $subtotal = $quantidade * $precoUnitario;
    ?>
    <tr>
        <td><?= $nomeProduto; ?></td>
        <td>R$ <?= $precoUnitario; ?></td>
        <td>
            <input type="number" id="quantidade_<?= $idCarrinho; ?>" value="<?= $quantidade; ?>" min="1">
            <button class="btn btn-info btn-sm" onclick="atualizarQuantidade(<?= $idCarrinho; ?>)">Atualizar</button>
        </td>
        <td>R$ <?= $subtotal; ?></td>
        <td>
            <button class="btn btn-danger btn-sm" onclick="removerDoCarrinho(<?= $idCarrinho; ?>)">Remover</button>
        </td>
    </tr>
<?php } ?>
</table>

<?php
$sqlTotal = "SELECT SUM(subtotal) AS total FROM carrinho WHERE id_usuario = ?";
$stmtTotal = mysqli_prepare($conexao, $sqlTotal);
mysqli_stmt_bind_param($stmtTotal, "i", $idUsuario);
mysqli_stmt_execute($stmtTotal);
$resultTotal = mysqli_stmt_get_result($stmtTotal);
$rowTotal = mysqli_fetch_assoc($resultTotal);
$valorTotal = $rowTotal['total'];
?>
<div style="text-align: right; margin-top:20px;">
    <p>Valor Total: R$ <?= $valorTotal; ?></p>
</div>

<div style="text-align: right; margin-top:20px;">
    <button class="btn btn-danger btn-sm" onclick="limparCarrinho()">Limpar Carrinho</button>
    <a href="pagamento.php?total=<?= $valorTotal; ?>" role="button" class="btn btn-primary btn-sm">Finalizar Compra</a>
</div>

    <script>
        function removerDoCarrinho(idCarrinho) {
            if (confirm("Tem certeza que deseja remover este produto do carrinho?")) {
                window.location = "remover_do_carrinho.php?id_carrinho=" + idCarrinho;
            }
        }

        function atualizarQuantidade(idCarrinho) {
            var novaQuantidade = $("#quantidade_" + idCarrinho).val();
            window.location = "atualizar_quantidade.php?id_carrinho=" + idCarrinho + "&quantidade=" + novaQuantidade;
        }

        function limparCarrinho() {
            if (confirm("Tem certeza que deseja limpar todo o carrinho?")) {
                window.location = "limpar_carrinho.php";
            }
        }
    </script>

</body>
</html>

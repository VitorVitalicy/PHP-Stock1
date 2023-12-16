<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Vendas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
</head>
<body>
    <?php
    session_start();

    $usuario = $_SESSION['usuario'];

    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php');
        exit();
    }

    include 'conexao.php';

    $sqlUsuario = "SELECT `nivel_usuario`, `id_usuario` FROM `usuarios` WHERE `mail_usuario` = ? and `status` = 'Ativo'";
    $stmtUsuario = mysqli_prepare($conexao, $sqlUsuario);
    mysqli_stmt_bind_param($stmtUsuario, "s", $usuario);
    mysqli_stmt_execute($stmtUsuario);
    $resultUsuario = mysqli_stmt_get_result($stmtUsuario);
    $rowUsuario = mysqli_fetch_array($resultUsuario);
    $nivel = $rowUsuario['nivel_usuario'];
    $idUsuario = $rowUsuario['id_usuario'];

    // Consulta ao banco de dados para obter os produtos do usuário
    $sql = "SELECT * FROM `estoque` WHERE `id_usuario` = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idUsuario);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    ?>

    <div class="container mt-5">
        <h3 class="text-center">Lista de Produtos</h3>
        <table class="table" id="table_id">
            <thead>
                <tr>
                    <th scope="col">Nome do Produto</th>
                    <th scope="col">Nº do Produto</th>
                    <th scope="col">Categoria</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Fornecedor</th>
                    <th scope="col">Ação</th>
                </tr>
            </thead>
            <?php
            while ($array = mysqli_fetch_array($result)) {
                $id_estoque = $array['id_estoque'];
                $numproduto = $array['numproduto'];
                $nomeproduto = $array['nomeproduto'];
                $categoria = $array['categoria'];
                $quantidade = $array['quantidade'];
                $fornecedor = $array['fornecedor'];
            ?>
            <tr>
                <td> <?php echo $nomeproduto ?> </td>
                <td> <?php echo $numproduto ?> </td>
                <td> <?php echo $categoria ?> </td>
                <td>
                    <input type="number" id="quantidade_<?php echo $id_estoque; ?>" value="1" min="1">
                    <input type="hidden" id="nomeProduto_<?php echo $id_estoque; ?>" value="<?php echo $nomeproduto; ?>">
                    <input type="hidden" id="precoProduto_<?php echo $id_estoque; ?>" value="<?php echo $array['preco_venda']; ?>">
                </td>
                <td> <?php echo $fornecedor ?> </td>
                <td>
                    <center>
                        <button type="button" class="btn btn-success btn-sm" onclick="adicionarAoCarrinho(<?php echo $id_estoque; ?>)">Adicionar ao Carrinho</button>
                    </center>
                </td>
            </tr>
            <?php } ?>
        </table>
        <div class="text-right mt-3">
            <a href="carrinho.php" role="button" class="btn btn-primary btn-sm">Ir para o Carrinho</a>
            <a href="menu.php" role="button" class="btn btn-primary btn-sm">Voltar ao Menu</a>
        </div>
    </div>

    <!-- Inclua os scripts necessários -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" crossorigin="anonymous"></script>
   
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>


    <script>
        function adicionarAoCarrinho(idEstoque) {
            var quantidade = $("#quantidade_" + idEstoque).val();
            var nomeProduto = $("#nomeProduto_" + idEstoque).val();
            var precoProduto = $("#precoProduto_" + idEstoque).val();

            $.ajax({
                type: "POST",
                url: "adicionar_ao_carrinho.php",
                data: {
                    idProduto: idEstoque,
                    nomeProduto: nomeProduto,
                    precoProduto: precoProduto,
                    quantidade: quantidade
                },
                dataType: "json",
                success: function (response) {
                    alert(response.message);
                },
                error: function (error) {
                    alert("Erro ao processar a solicitação.");
                }
            });
        }

        $(document).ready(function () {
            $('#table_id').DataTable({
                "language": {
                    "lengthMenu": "Mostrando _MENU_ registros por página",
                    "zeroRecords": "Nada encontrado",
                    "info": "Mostrando _PAGE_ de _PAGES_",
                    "infoEmpty": "Nenhum registro encontrado",
                    "infoFiltered": "(Filtrado de _MAX_ registros totais)",
                    "search": "Pesquisar:"
                }
            });
        });
    </script>
</body>
</html>

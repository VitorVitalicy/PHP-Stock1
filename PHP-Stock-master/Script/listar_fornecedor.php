<!-- listar_fornecedor.php -->

<?php
session_start();

include 'conexao.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: index.php');
    exit();
}

$usuario = $_SESSION['usuario'];

$sql = "SELECT `nivel_usuario`, `id_usuario` FROM `usuarios` WHERE `mail_usuario` = '$usuario' and `status` = 'Ativo'";
$buscar = mysqli_query($conexao, $sql);

// Verifique se a consulta foi bem-sucedida
if ($buscar) {
    $array = mysqli_fetch_array($buscar);
    $nivel = $array['nivel_usuario'];
    $idUsuario = $array['id_usuario'];
} else {
    echo "Erro na consulta: " . mysqli_error($conexao);
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Fornecedores</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
</head>

<body>

    <div class="container" style="margin-top: 40px">

        <center>
            <h3>Lista de Fornecedores</h3>
        </center>
        <br>
        <br>
        <table class="table" id="table_id">
            <thead>
                <tr>
                    <th scope="col">Nome do Fornecedor</th>
                    <!-- Adicione mais colunas conforme necessário -->
                    <th scope="col">Ação</th>
                </tr>
            </thead>

            <?php
            $sql = "SELECT * FROM `fornecedor` WHERE `id_usuario` = '$idUsuario'";
            $busca = mysqli_query($conexao, $sql);

            while ($array = mysqli_fetch_array($busca)) {

                $id_fornecedor = $array['id_fornecedor'];
                $nome_fornecedor = $array['nome_fornecedor'];

                ?>
                <tr>
                    <td style="vertical-align: inherit;"> <?php echo $nome_fornecedor ?> </td>
                    <!-- Adicione mais colunas conforme necessário -->
                    <td style="vertical-align: inherit;">
                        <!-- Adicione links de ação, por exemplo, editar, excluir, etc. -->
                        <?php
                        if (($nivel == 1) || ($nivel == 2)) {
                        ?>
                            <a href="editar_fornecedor.php?id=<?php echo $id_fornecedor ?>" role="button"
                                class="btn btn-warning btn-sm"><i class="far fa-edit"></i>&nbsp; Editar</a>
                        <?php } ?>
                        <?php
                        if ($nivel == 1) {
                        ?>
                            <a href="deletar_fornecedor.php?id=<?php echo $id_fornecedor ?>" role="button"
                                class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i>&nbsp; Excluir</a>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>

        </table>

        <div style="text-align: right; margin-top:20px;">
            <!-- Adicione links de ação conforme necessário -->
            <?php
            if ($nivel == 1 || $nivel == 2) {
            ?>
                <a href="adicionar_fornecedor.php" role="button" class="btn btn-success btn-sm">Cadastrar fornecedor</a>
            <?php } ?>
            <a href="menu.php" role="button" class="btn btn-primary btn-sm">Voltar ao menu</a>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous">
    </script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js">
    </script>

    <script>
        $(document).ready(function() {
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

<?php
include 'conexao.php';

session_start();

$numproduto = $_POST['numproduto'];
$nomeproduto = $_POST['nomeproduto'];
$categoria = $_POST['categoria'];
$quantidade = $_POST['quantidade'];
$preco_compra = $_POST['preco_compra'];
$preco_venda = $_POST['preco_venda'];
$fornecedor = $_POST['fornecedor'];
$id_usuario = $_SESSION['id_usuario'];

$sql = "INSERT INTO `estoque`(`numproduto`, `nomeproduto`, `categoria`, `quantidade`, `preco_compra`, `preco_venda`, `fornecedor`, `id_usuario`) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "issddssi", $numproduto, $nomeproduto, $categoria, $quantidade, $preco_compra, $preco_venda, $fornecedor, $id_usuario);
$inserir = mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($conexao);
?>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<div class="container" style="width: 500px; margin-top: 30px;">
    <center>
        <?php
            if ($inserir) {
                echo "<h4>Produto adicionado com sucesso!</h4>";
            } else {
                echo "<h4>Falha ao adicionar o produto.</h4>";
            }
        ?>
        <div style="margin-top: 10px;">
            <a href="adicionar_produto.php" class="btn btn-sm btn-warning" style="color: #fff">Voltar</a>
        </div>
    </center>
</div>

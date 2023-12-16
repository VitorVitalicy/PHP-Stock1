<?php
include 'conexao.php';

session_start();

$fornecedor = $_POST['fornecedor'];
$id_usuario = $_SESSION['id_usuario'];

$sql = "INSERT INTO `fornecedor`(`nome_fornecedor`, `id_usuario`) VALUES (?, ?)";

$stmt = mysqli_prepare($conexao, $sql);
mysqli_stmt_bind_param($stmt, "si", $fornecedor, $id_usuario);
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
                echo "<h4>Fornecedor adicionado com sucesso!</h4>";
            } else {
                echo "<h4>Erro ao adicionar fornecedor. Por favor, tente novamente.</h4>";
                echo "Erro MySQL: " . mysqli_error($conexao);
            }
        ?>
    </center>
    <div style="padding-top: 20px;">
        <center>
            <a href="menu.php" role="button" class="btn btn-primary btn-sm">Voltar ao menu</a>
        </center>
    </div>
</div>

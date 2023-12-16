<?php
// Inclua o arquivo de conexão com o banco de dados
include 'conexao.php';

// Certifique-se de iniciar a sessão para acessar $_SESSION['id_usuario']
session_start();

// Recupere o ID do usuário da sessão
$id_usuario = $_SESSION['id_usuario'];

// Recupere a categoria do formulário
$catproduto = $_POST['catproduto'];

// Evite SQL injection sem prepared statements
$catproduto = mysqli_real_escape_string($conexao, $catproduto);

// Crie a consulta SQL
$sql = "INSERT INTO `categoria` (`categoria`, `id_usuario`) VALUES ('$catproduto', '$id_usuario')";



echo "ID do usuário: " . $_SESSION['id_usuario'];




// Execute a consulta
$inserir = mysqli_query($conexao, $sql);

// Verifique se a inserção foi bem-sucedida
if ($inserir) {
    echo "Inserção bem-sucedida!";
} else {
    echo "Erro na inserção: " . mysqli_error($conexao);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Adicionar Categoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
</head>

<body>
    <div class="container" style="width: 500px; margin-top: 30px;">
        <center>
            <?php
                if ($inserir) {
                    echo "<h4>Categoria adicionada com sucesso!</h4>";
                } else {
                    echo "<h4>Erro ao adicionar categoria. Por favor, tente novamente.</h4>";
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
</body>

</html>

<?php
// Feche a conexão
mysqli_close($conexao);
?>

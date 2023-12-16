<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD PHP</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
        crossorigin="anonymous">
    <style>
        #container {
            width: 500px;
        }
    </style>
</head>

<body>

    <div class="container" style="margin-top: 40px" id="container">
        <h3>Formulário de cadastro</h3>

        <form action="_inserir_produto.php" method="post" style="margin-top: 20px">
            <div class="form-group">
                <label>Número do produto</label>
                <input type="number" class="form-control" name="numproduto" placeholder="Insira o número do produto"
                    required>
            </div>
            <div class="form-group">
                <label>Nome do produto</label>
                <input type="text" class="form-control" name="nomeproduto" placeholder="Insira o nome do produto"
                    required>
            </div>
            <div class="form-group">
                <label>Categoria</label>
                <select class="form-control" name="categoria" required>
                    <?php
                    include 'conexao.php';

                    $sql = "SELECT * FROM `categoria` ORDER BY categoria ASC";
                    $buscar = mysqli_query($conexao, $sql);

                    while ($array = mysqli_fetch_array($buscar)) {
                        $nome_categoria = $array['categoria'];
                        echo "<option value='$nome_categoria'>$nome_categoria</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Quantidade</label>
                <input type="number" class="form-control" name="quantidade" placeholder="Insira a quantidade" required>
            </div>
            <div class="form-group">
                <label>Fornecedor</label>
                <select class="form-control" name="fornecedor" required>
                    <?php
                    $sql2 = "SELECT * FROM `fornecedor`";
                    $buscar2 = mysqli_query($conexao, $sql2);

                    while ($array2 = mysqli_fetch_array($buscar2)) {
                        $nome_fornecedor = $array2['nome_fornecedor'];
                        echo "<option value='$nome_fornecedor'>$nome_fornecedor</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label>Preço de Compra</label>
                <input type="number" step="0.01" class="form-control" name="preco_compra"
                    placeholder="Insira o preço de compra" required>
            </div>
            <div class="form-group">
                <label>Preço de Venda</label>
                <input type="number" step="0.01" class="form-control" name="preco_venda"
                    placeholder="Insira o preço de venda" required>
            </div>
            <div style="text-align: right;">
                <a href="menu.php" role="button" class="btn btn-primary btn-sm">Voltar ao menu</a>
                <button type="submit" id="botao" class="btn btn-success btn-sm">Cadastrar</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
        integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
        crossorigin="anonymous"></script>
</body>

</html>

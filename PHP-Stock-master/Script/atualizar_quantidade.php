<?php
// Verifica se a solicitação é via POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Inclui o arquivo de conexão
    include 'conexao.php';

    // Obtém os dados do POST
    $idProduto = $_POST['idProduto'];
    $novaQuantidade = $_POST['novaQuantidade'];

    // Atualiza a quantidade no carrinho no banco de dados
    $query = "UPDATE carrinho SET quantidade = ? WHERE id_produto = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "ii", $novaQuantidade, $idProduto);

    if (mysqli_stmt_execute($stmt)) {
        echo "Quantidade atualizada no carrinho com sucesso";
    } else {
        echo "Erro ao atualizar a quantidade no carrinho";
    }

    // Fecha a conexão
    mysqli_close($conexao);
} else {
    echo "Método não permitido";
}
?>

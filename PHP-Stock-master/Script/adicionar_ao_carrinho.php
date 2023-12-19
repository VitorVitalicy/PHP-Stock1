session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include 'conexao.php';

    $idProduto = $_POST['idProduto'];
    $nomeProduto = $_POST['nomeProduto'];
    $precoProduto = $_POST['precoProduto'];
    $quantidade = $_POST['quantidade'];

    // Verifica se o produto já está no carrinho
    $query = "SELECT * FROM carrinho WHERE id_produto = ?";
    $stmt = mysqli_prepare($conexao, $query);
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        // Atualiza a quantidade se o produto já estiver no carrinho
        $query = "UPDATE carrinho SET quantidade = quantidade + ? WHERE id_produto = ?";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "ii", $quantidade, $idProduto);
        $success = mysqli_stmt_execute($stmt);
    } else {
        // Adiciona o produto ao carrinho se não estiver presente
        $query = "INSERT INTO carrinho (id_produto, quantidade) VALUES (?, ?)";
        $stmt = mysqli_prepare($conexao, $query);
        mysqli_stmt_bind_param($stmt, "ii", $idProduto, $quantidade);
        $success = mysqli_stmt_execute($stmt);
    }

    mysqli_close($conexao);

    // Retorna uma resposta JSON para o JavaScript
    if ($success) {
        echo json_encode(array('status' => 'success', 'message' => 'Produto adicionado ao carrinho com sucesso.'));
    } else {
        echo json_encode(array('status' => 'error', 'message' => 'Erro ao adicionar o produto ao carrinho.'));
    }
} else {
    // Retorna uma resposta JSON para o JavaScript em caso de método não permitido
    echo json_encode(array('status' => 'error', 'message' => 'Método não permitido.'));
}
?>

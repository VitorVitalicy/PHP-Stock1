<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Pagamento</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            margin-top: 40px;
        }

        h3 {
            text-align: center;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #007bff;
            color: #fff;
            padding: 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="container">
        <h3>Pagamento</h3>

        <div class="row">
            <div class="col-md-6 offset-md-3">
            <form action="processar_pagamento.php" method="post">
    <div class="form-group">
        <label for="total">Valor Total:</label>
        <input type="text" class="form-control" id="total" name="total" value="<?= isset($_GET['total']) ? $_GET['total'] : ''; ?>" readonly>
    </div>
    <div class="form-group">
        <label for="forma_pagamento">Forma de Pagamento:</label>
        <select class="form-control" id="forma_pagamento" name="forma_pagamento" required>
            <option value="debito">Débito</option>
            <option value="credito">Crédito</option>
            <option value="pix">PIX</option>
            <option value="dinheiro">Dinheiro</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Pagar</button>
</form>
            </div>
        </div>
    </div>

</body>
</html>

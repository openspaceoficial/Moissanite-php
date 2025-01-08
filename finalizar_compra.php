<?php
session_start(); // Inicia a sessão

// Verifica se há itens no carrinho
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    echo "<p>Seu carrinho está vazio. <a href='orcamento.php'>Voltar</a></p>";
    exit;
}

// Função para calcular o total
function calcularTotal($carrinho) {
    $total = 0;
    foreach ($carrinho as $item) {
        $precoBase = ($item['pedra'] === 'moissanite') ? 50 : 30; // Simulação de preços
        $total += $precoBase * $item['quantidade'];
    }
    return $total;
}

$totalCarrinho = calcularTotal($_SESSION['carrinho']);
$frete = $_SESSION['frete'] ?? 0; // Recupera o frete calculado, se existir
$totalComFrete = $totalCarrinho + $frete;

// Processamento do formulário de pagamento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $email = $_POST['email'] ?? '';
    $metodoPagamento = $_POST['metodo_pagamento'] ?? '';

    // Validação básica
    if (empty($nome) || empty($email) || empty($metodoPagamento)) {
        $erro = "Por favor, preencha todos os campos.";
    } else {
        // Simula a finalização da compra (salvar em banco, enviar e-mail, etc.)
        echo "<p style='color: green;'>Compra realizada com sucesso! Obrigado, $nome.</p>";
        session_destroy(); // Limpa o carrinho após a compra
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pagamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
        }

        .total {
            font-weight: bold;
            text-align: right;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
        }

        input, select, button {
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 5px;
            outline: none;
        }

        button {
            background-color: #4caf50;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .erro {
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Confirmar Pagamento</h2>

        <h3>Resumo do Pedido</h3>
        <table>
            <thead>
                <tr>
                    <th>Pedra</th>
                    <th>Formato</th>
                    <th>Tamanho (mm)</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <tr>
                        <td><?php echo ucfirst($item['pedra']); ?></td>
                        <td><?php echo ucfirst($item['formato']); ?></td>
                        <td><?php echo $item['tamanho']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$<?php echo ($item['pedra'] === 'moissanite') ? '50.00' : '30.00'; ?></td>
                        <td>R$<?php echo ($item['pedra'] === 'moissanite' ? 50 : 30) * $item['quantidade']; ?>.00</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="total">Total dos itens: R$<?php echo number_format($totalCarrinho, 2, ',', '.'); ?></p>
        <p class="total">Frete: R$<?php echo number_format($frete, 2, ',', '.'); ?></p>
        <p class="total">Total com Frete: R$<?php echo number_format($totalComFrete, 2, ',', '.'); ?></p>

        <h3>Informações de Pagamento</h3>
        <?php if (isset($erro)): ?>
            <p class="erro"><?php echo $erro; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="nome">Nome Completo:</label>
            <input type="text" name="nome" id="nome" required>

            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="metodo_pagamento">Método de Pagamento:</label>
            <select name="metodo_pagamento" id="metodo_pagamento" required>
                <option value="cartao_credito">Cartão de Crédito</option>
                <option value="boleto">Boleto Bancário</option>
                <option value="pix">PIX</option>
            </select>

            <button type="submit">Finalizar Compra</button>
        </form>
    </div>
</body>
</html>

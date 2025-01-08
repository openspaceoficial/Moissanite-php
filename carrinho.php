<?php
session_start(); // Inicia a sessão
// Cria um item de teste
$itemTeste = [
    'imagem' => 'caminho_para_imagem_do_produto.jpg', // Atualize para o caminho real
    'pedra' => 'diamante',
    'formato' => 'redondo',
    'tamanho' => 5,
    'quantidade' => 2
];

// Adiciona o item de teste ao carrinho
$_SESSION['carrinho'][] = $itemTeste;

// Verifica se a variável de carrinho existe, senão cria um novo carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}


if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) :
?>
    <div class="container-carrinho">
        <h2>Itens no Carrinho</h2>
        <ul class="carrinho-lista">
            <?php foreach ($_SESSION['carrinho'] as $index => $item) : ?>
                <li class="carrinho-item">
                    <img src="<?php echo $item['imagem']; ?>" alt="Imagem do Produto" class="produto-imagem">
                    <div class="produto-detalhes">
                        <span><strong>Pedra:</strong> <?php echo ucfirst($item['pedra']); ?></span>
                        <span><strong>Formato:</strong> <?php echo ucfirst($item['formato']); ?></span>
                        <span><strong>Tamanho:</strong> <?php echo $item['tamanho']; ?>mm</span>
                        <span class="quantidade"><strong>Quantidade:</strong> <?php echo $item['quantidade']; ?> unidades</span>
                    </div>
                    <!-- Botão de lixeira -->
                    <form action="remover_item.php" method="POST" onsubmit="return confirmarExclusao();" class="remover-form">
                        <input type="hidden" name="indice" value="<?php echo $index; ?>">
                        <button type="submit" class="btn-remover" title="Remover item">
                            🗑️
                        </button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
        
        <div class="botoes-carrinho">
            <a href="finalizar_compra.php"><button class="finalizar">Finalizar Compra</button></a>
            <a href="orcamento.php"><button class="continuar">Continuar Comprando</button></a>
        </div>
    </div>
<?php else: ?>
    <div class="container-carrinho">
        <p>Seu carrinho está vazio.</p>
        <a href="orcamento.php"><button class="continuar">Voltar à Loja</button></a>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romulo Moissanite</title>
    <link rel="stylesheet" href="styless.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
        }

        .container-carrinho {
            max-width: 1200px;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #444;
            text-align: center;
        }

        .carrinho-lista {
            list-style-type: none;
            padding: 0;
        }

        .carrinho-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            border-bottom: 1px solid #ddd;
            transition: transform 0.3s ease;
        }

        .carrinho-item:hover {
            transform: scale(1.02);
        }

        .produto-imagem {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .produto-detalhes {
            flex-grow: 1;
            font-size: 16px;
            color: #555;
        }

        .produto-detalhes span {
            display: block;
            margin-bottom: 8px;
        }

        .produto-detalhes .quantidade {
            font-weight: bold;
            color: #333;
        }

        .remover-form {
            margin-left: 20px;
        }

        .btn-remover {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 22px;
            color: #ff0000;
            transition: transform 0.2s, color 0.3s;
        }

        .btn-remover:hover {
            transform: scale(1.2);
            color: #cc0000;
        }

        .botoes-carrinho {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }

        .botoes-carrinho a {
            text-decoration: none;
        }

        .botoes-carrinho button {
            padding: 15px 30px;
            font-size: 18px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        .finalizar {
            background-color: #4caf50;
            color: white;
        }

        .finalizar:hover {
            background-color: #45a049;
        }

        .continuar {
            background-color: #2196f3;
            color: white;
        }

        .continuar:hover {
            background-color: #1e87e0;
        }

        .continuar,
        .finalizar {
            width: 200px;
        }

        .botoes-carrinho button:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .carrinho-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .remover-form {
                margin-left: 0;
                margin-top: 10px;
            }

            .produto-detalhes {
                margin-top: 10px;
            }

            .botoes-carrinho {
                flex-direction: column;
                gap: 10px;
            }

            .botoes-carrinho button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <script>
        function confirmarExclusao() {
            return confirm("Tem certeza que deseja remover este item do carrinho?");
        }
    </script>
</body>
</html>

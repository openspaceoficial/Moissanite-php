<?php
session_start(); // Inicia a sessão

// Verifica se a variável de carrinho existe, senão cria um novo carrinho
if (!isset($_SESSION['carrinho'])) {
    $_SESSION['carrinho'] = [];
}

// Lógica para adicionar novos itens
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar_item'])) {
    $tipoPedra = strtolower(trim($_POST['pedra']));
    $imagem = "imagens/{$tipoPedra}.jpg"; // Carrega imagem com base no tipo de pedra

    // Simula preços com base no tipo de pedra
    $precos = [
        'diamante' => 500,
        'moissanite' => 300,
        'esmeralda' => 200,
    ];

    $preco = $precos[$tipoPedra] ?? 100; // Preço padrão se não encontrado

    $novoItem = [
        'imagem' => file_exists($imagem) ? $imagem : 'imagens/default.jpg',
        'pedra' => htmlspecialchars($_POST['pedra'] ?? 'desconhecido'),
        'formato' => htmlspecialchars($_POST['formato'] ?? 'desconhecido'),
        'tamanho' => intval($_POST['tamanho']),
        'quantidade' => intval($_POST['quantidade']),
        'preco' => $preco,
    ];

    $_SESSION['carrinho'][] = $novoItem;
    header('Location: ' . $_SERVER['PHP_SELF']); // Atualiza a página
    exit;
}


// Lógica para editar a quantidade
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_quantidade'])) {
    $indice = intval($_POST['indice']);
    $novaQuantidade = intval($_POST['nova_quantidade']);
    if ($novaQuantidade > 0 && isset($_SESSION['carrinho'][$indice])) {
        $_SESSION['carrinho'][$indice]['quantidade'] = $novaQuantidade;
    }
    header('Location: ' . $_SERVER['PHP_SELF']); // Atualiza a página
    exit;
}

// Lógica para remover itens do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remover_item'])) {
    $indice = intval($_POST['indice']);
    if (isset($_SESSION['carrinho'][$indice])) {
        array_splice($_SESSION['carrinho'], $indice, 1); // Remove o item do carrinho
    }
    header('Location: ' . $_SERVER['PHP_SELF']); // Atualiza a página
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romulo Moissanite</title>
    <link rel="stylesheet" href="carrinho.css">
</head>
<body>

<div class="container-carrinho">
    <h2>Itens no Carrinho</h2>
    <?php if (!empty($_SESSION['carrinho'])): ?>
        <ul class="carrinho-lista">
    <?php 
    $totalCarrinho = 0;
    foreach ($_SESSION['carrinho'] as $index => $item): 
        $preco = $item['preco'] ?? 0; // Usa 0 como valor padrão se 'preco' não estiver definido
        $subtotal = $preco * $item['quantidade'];
        $totalCarrinho += $subtotal;
    ?>
        <li class="carrinho-item">
            <img src="<?php echo htmlspecialchars($item['imagem'], ENT_QUOTES, 'UTF-8'); ?>" alt="Imagem do Produto" class="produto-imagem">
            <div class="produto-detalhes">
                <span><strong>Pedra:</strong> <?php echo ucfirst(htmlspecialchars($item['pedra'], ENT_QUOTES, 'UTF-8')); ?></span>
                <span><strong>Formato:</strong> <?php echo ucfirst(htmlspecialchars($item['formato'], ENT_QUOTES, 'UTF-8')); ?></span>
                <span><strong>Tamanho:</strong> <?php echo intval($item['tamanho']); ?>mm</span>
                <span><strong>Quantidade:</strong> <?php echo intval($item['quantidade']); ?></span>
                <span><strong>Preço Unitário:</strong> R$<?php echo number_format($preco, 2, ',', '.'); ?></span>
                <span><strong>Subtotal:</strong> R$<?php echo number_format($subtotal, 2, ',', '.'); ?></span>
            </div>
            <form action="" method="POST" class="editar-quantidade-form">
                <input type="hidden" name="indice" value="<?php echo $index; ?>">
                <button type="button" class="btn-editar" onclick="habilitarEdicao(this, <?php echo $index; ?>)">✏️</button>
            </form>
            <form action="" method="POST" onsubmit="return confirmarExclusao();" class="remover-form">
                <input type="hidden" name="indice" value="<?php echo $index; ?>">
                <button type="submit" name="remover_item" class="btn-remover" title="Remover item">
                    🗑️
                </button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>


<!-- Exibe o total do carrinho -->
<div class="total-carrinho">
    <h3>Total do Carrinho: R$<?php echo number_format($totalCarrinho, 2, ',', '.'); ?></h3>
</div>

        <div class="botoes-carrinho">
            <a href="finalizar_compra.php"><button class="finalizar">Finalizar Compra</button></a>
            <a href="../orcamento/orcamento.php"><button class="continuar">Continuar Comprando</button></a>
        </div>
    <?php else: ?>
        <p>Seu carrinho está vazio.</p>
        <a href="../orcamento/orcamento.php"><button class="continuar">Voltar à Loja</button></a>
    <?php endif; ?>
</div>

<!-- Formulário para adicionar novos itens -->
<div class="container-adicionar">
    <h2>Adicionar Novo Item</h2>
    <form action="" method="POST" class="form-adicionar">
        <label for="pedra">Tipo de Pedra:</label>
        <input type="text" name="pedra" id="pedra" placeholder="Ex: Diamante" required>

        <label for="formato">Formato:</label>
        <input type="text" name="formato" id="formato" placeholder="Ex: Redondo" required>

        <label for="tamanho">Tamanho (mm):</label>
        <input type="number" name="tamanho" id="tamanho" placeholder="Ex: 5" required min="1">

        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" placeholder="Ex: 1" required min="1">

        <button type="submit" name="adicionar_item" class="btn-adicionar">Adicionar ao Carrinho</button>
    </form>
</div>

<script>
    function confirmarExclusao() {
        return confirm("Tem certeza que deseja remover este item do carrinho?");
    }

    function habilitarEdicao(botao, indice) {
        botao.parentElement.innerHTML = `
            <form action="" method="POST" style="display: inline;">
                <input type="hidden" name="indice" value="${indice}">
                <input type="number" name="nova_quantidade" value="1" min="1" style="width: 60px;">
                <button type="submit" name="editar_quantidade" class="btn-atualizar">Salvar</button>
            </form>`;
    }
</script>

</body>
</html>

<?php
session_start();
include(__DIR__ . '/../config/database.php'); // Inclui a conexão

// Verifica se o cliente está logado
if (!isset($_SESSION['usuario_id'])) {
    echo "Você precisa estar logado para visualizar seus pedidos.";
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

// Consulta para obter todos os pedidos do cliente
$sql = "SELECT * FROM pedidos WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC";
$stmt = $conn->prepare($sql); // Alterado para $conn
$stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acompanhar Pedido</title>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <style>
       body {
        font-family: 'Arial', sans-serif;
        background-color: #e9ecef;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }
    .container {
        max-width: 900px;
        width: 90%;
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.5s ease-in-out;
    }
    .container h2 {
        font-size: 24px;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        border-bottom: 2px solid #38a169;
        padding-bottom: 10px;
        display: inline-block;
    }
    .pedido-info {
        background: #f9f9f9;
        padding: 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        border-left: 4px solid #38a169;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }
    .pedido-info p {
        margin: 5px 0;
        color: #555;
        font-size: 16px;
    }
    .pedido-info p strong {
        color: #333;
    }
    .btn-pagar {
        display: inline-block;
        margin-top: 10px;
        padding: 10px 20px;
        background: #38a169;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease;
    }
    .btn-pagar:hover {
        background: #2f855a;
    }
    #qrCodeContainer_ {
        text-align: center;
        margin-top: 15px;
    }
    a {
        color: #38a169;
        text-decoration: none;
        font-weight: bold;
    }
    a:hover {
        text-decoration: underline;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    </style>
</head>
<body>
    <div class="container">
        <h2>Acompanhar Pedido</h2>

        <?php foreach ($pedidos as $pedido): ?>
    <div class="pedido-info">
        <p><strong>ID do Pedido:</strong> <?php echo $pedido['id']; ?></p>
        <p><strong>Status:</strong> <span id="status_<?php echo $pedido['id']; ?>"><?php echo $pedido['status']; ?></span></p>
        <p><strong>Status Detalhado:</strong> <?php echo $pedido['status_detalhado']; ?></p>
        <p><strong>Total:</strong> R$<?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>

        <?php if (!empty($pedido['codigo_rastreamento'])): ?>
            <p>
                <strong>Código de Rastreamento:</strong> 
                <a href="https://www2.correios.com.br/sistemas/rastreamento/default.cfm?objeto=<?php echo $pedido['codigo_rastreamento']; ?>" 
                   target="_blank">
                   <?php echo $pedido['codigo_rastreamento']; ?>
                </a>
            </p>
        <?php else: ?>
            <p><em>Código de rastreamento ainda não disponível.</em></p>
        <?php endif; ?>

        <?php if ($pedido['status'] === 'Pendente de Pagamento'): ?>
            <div id="opcoesPagamento_<?php echo $pedido['id']; ?>">
                <button class="btn-pagar" onclick="pagarPix(<?php echo $pedido['id']; ?>)">Pagar com Pix</button>
                <button class="btn-pagar" onclick="gerarBoleto(<?php echo $pedido['id']; ?>)">Gerar Boleto</button>
                <button class="btn-pagar" onclick="pagarCartao(<?php echo $pedido['id']; ?>)">Pagar com Cartão</button>
            </div>
        <?php else: ?>
            <p>Pagamento já realizado.</p>
        <?php endif; ?>
    </div>
<?php endforeach; ?>


    <script>
        function pagarPix(pedidoId) {
            alert("Pagamento com Pix para o pedido " + pedidoId + " iniciado.");
            // Aqui você pode fazer uma requisição AJAX para gerar o QR Code do Pix
        }

        function gerarBoleto(pedidoId) {
            alert("Boleto gerado para o pedido " + pedidoId + ".");
            // Aqui você pode redirecionar para a página que gera o boleto
        }

        function pagarCartao(pedidoId) {
            alert("Pagamento com cartão para o pedido " + pedidoId + " iniciado.");
            // Aqui você pode abrir um formulário ou redirecionar para o pagamento com cartão
        }
    </script>
</body>
</html>
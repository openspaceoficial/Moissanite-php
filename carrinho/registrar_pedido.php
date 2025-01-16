<?php
session_start();
include('../config/database.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se o usuário está logado
    if (!isset($_SESSION['usuario_id'])) {
        header("Location: ../login/login.php");
        exit();
    }

    $usuario_id = $_SESSION['usuario_id'];
    $frete = floatval($_POST['frete']);
    $total = floatval($_POST['total']);
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $status = "Pendente";

    // Verifica se já existe um pedido pendente para o usuário
    $sql_check = "SELECT id FROM pedidos WHERE usuario_id = :usuario_id AND status = 'Pendente'";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        // Se já existe um pedido pendente, redireciona para a página de acompanhamento
        $pedido_existente = $stmt_check->fetch(PDO::FETCH_ASSOC);
        header("Location: acompanhar_pedido.php?pedido_id=" . $pedido_existente['id']);
        exit();
    }

    // Insere um novo pedido, pois não há nenhum pendente
    $sql = "INSERT INTO pedidos (usuario_id, total, metodo_pagamento, status) VALUES (:usuario_id, :total, :metodo_pagamento, :status)";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $stmt->bindValue(':total', $total, PDO::PARAM_STR);
    $stmt->bindValue(':metodo_pagamento', $metodo_pagamento, PDO::PARAM_STR);
    $stmt->bindValue(':status', $status, PDO::PARAM_STR);

    if ($stmt->execute()) {
        // Redireciona para a página de acompanhamento do novo pedido
        header("Location: acompanhar_pedido.php?pedido_id=" . $conn->lastInsertId());
        exit();
    } else {
        echo "Erro ao registrar o pedido: " . $stmt->errorInfo()[2];
    }
} else {
    echo "Requisição inválida.";
}
?>

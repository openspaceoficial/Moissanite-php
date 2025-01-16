<?php
session_start();
include '../config/database.php'; // Incluindo o arquivo de conexão com o banco de dados

// Verifique se o cliente está logado e obtenha o ID do cliente
$cliente_id = $_SESSION['cliente_id'] ?? 1; // Use um ID de cliente válido aqui ou de uma variável de sessão

// Consulta para obter o histórico de compras do cliente
$query = "SELECT * FROM historico_compras WHERE cliente_id = :cliente_id ORDER BY data DESC";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
$stmt->execute();

$historico_compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Histórico de Compras</h2>

        <?php if (empty($historico_compras)) : ?>
            <p>Você ainda não fez nenhuma compra.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tipo</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historico_compras as $compra) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($compra['id']); ?></td>
                            <td><?php echo htmlspecialchars($compra['tipo']); ?></td>
                            <td>R$ <?php echo number_format($compra['total'], 2, ',', '.'); ?></td>
                            <td><?php echo htmlspecialchars($compra['status']); ?></td>
                            <td><?php echo date('d/m/Y H:i:s', strtotime($compra['data'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>
</body>
</html>

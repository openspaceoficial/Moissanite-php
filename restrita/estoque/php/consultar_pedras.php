<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pedrasDB';

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Validação e sanitização de entrada
$pedra = $_GET['pedra'] ?? '';
$formato = $_GET['formato'] ?? '';
$mm = $_GET['mm'] ?? '';

if (!in_array($pedra, ['moissanite', 'zirconia'])) {
    die("Erro: Tipo de pedra inválido.");
}

// Construção segura da consulta
$sql = "SELECT ? AS Pedra, Formato, CT, MM, Qpl, Ideal, Estoque_Unidade, Estoque_Quilate, Preco_Venda FROM $pedra WHERE 1=1";

$params = [$pedra];
$types = 's'; // Tipo para bind_param: 's' para string

if (!empty($formato)) {
    $sql .= " AND Formato = ?";
    $params[] = $formato;
    $types .= 's';
}
if (!empty($mm)) {
    $sql .= " AND MM = ?";
    $params[] = $mm;
    $types .= 's';
}

// Preparar e executar a consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conn->error);
}
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Gera a tabela HTML
echo "<table>
        <tr>
            <th>Pedra</th>
            <th>Formato</th>
            <th>CT</th>
            <th>MM</th>
            <th>Qpl</th>
            <th>Ideal</th>
            <th>Estoque_Unidade</th>
            <th>Estoque_Quilate</th>
            <th>Preço de Venda</th>
        </tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['Pedra']) . "</td>
            <td>" . htmlspecialchars($row['Formato']) . "</td>
            <td>" . htmlspecialchars($row['CT']) . "</td>
            <td>" . htmlspecialchars($row['MM']) . "</td>
            <td>" . htmlspecialchars($row['Qpl']) . "</td>
            <td>" . htmlspecialchars($row['Ideal']) . "</td>
            <td>" . htmlspecialchars($row['Estoque_Unidade']) . "</td>
            <td>" . htmlspecialchars($row['Estoque_Quilate']) . "</td>
            <td>" . htmlspecialchars($row['Preco_Venda']) . "</td>
          </tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>

<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'moissanite';
$porta = 3306;

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco, $porta);
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os filtros
$formato = $_GET['formato'] ?? '';
$mm = $_GET['mm'] ?? '';

// Construção da consulta com filtros
$sqlMoissanite = "SELECT 'moissanite' AS Pedra, Formato, CT, MM, Qpl, Ideal, Estoque_Unidade, Estoque_Quilate, Fator, Preco_Venda 
                  FROM moissanite WHERE 1=1";
$sqlZirconia = "SELECT 'zirconia' AS Pedra, Formato, CT, MM, Qpl, Ideal, Estoque_Unidade, Estoque_Quilate, Fator, Preco_Venda 
                FROM zirconia WHERE 1=1";

$params = [];
$types = '';

// Aplicar filtros à tabela moissanite
if (!empty($formato)) {
    $sqlMoissanite .= " AND Formato = ?";
    $params[] = $formato;
    $types .= 's';
}
if (!empty($mm)) {
    $sqlMoissanite .= " AND MM = ?";
    $params[] = $mm;
    $types .= 's';
}

// Aplicar filtros à tabela zirconia
if (!empty($formato)) {
    $sqlZirconia .= " AND Formato = ?";
    $params[] = $formato;
    $types .= 's';
}
if (!empty($mm)) {
    $sqlZirconia .= " AND MM = ?";
    $params[] = $mm;
    $types .= 's';
}

// União das consultas
$sql = "$sqlMoissanite UNION $sqlZirconia";

// Preparar a consulta
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro ao preparar a consulta: " . $conn->error);
}

// Vincular parâmetros, se existirem
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$resultado = $stmt->get_result();

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
            <th>Fator</th>
            <th>Preço de Venda</th>
        </tr>";

while ($row = $resultado->fetch_assoc()) {
    echo "<tr>
            <td>" . htmlspecialchars($row['Pedra']) . "</td>
            <td>" . htmlspecialchars($row['Formato']) . "</td>
            <td>" . htmlspecialchars($row['CT']) . "</td>
            <td>" . htmlspecialchars($row['MM']) . "</td>
            <td>" . htmlspecialchars($row['Qpl']) . "</td>
            <td>" . htmlspecialchars($row['Ideal']) . "</td>
            <td>" . htmlspecialchars($row['Estoque_Unidade']) . "</td>
            <td>" . htmlspecialchars($row['Estoque_Quilate']) . "</td>
            <td>" . htmlspecialchars($row['Fator']) . "</td>
            <td>" . htmlspecialchars($row['Preco_Venda']) . "</td>
          </tr>";
}

echo "</table>";

$stmt->close();
$conn->close();
?>

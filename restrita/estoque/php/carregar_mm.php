<?php
$conn = new mysqli('localhost', 'root', '', 'pedrasDB', 3306);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Valida o parâmetro 'pedra'
$pedra = $_GET['pedra'] ?? '';
if (!in_array($pedra, ['moissanite', 'zirconia'])) {
    echo json_encode(['error' => 'Tipo de pedra inválido.']);
    exit;
}

// Valida o parâmetro 'formato'
$formato = $_GET['formato'] ?? '';
if (empty($formato)) {
    echo json_encode(['error' => 'Formato é obrigatório.']);
    exit;
}

// Consulta segura usando prepared statement
$sql = "SELECT DISTINCT MM FROM $pedra WHERE Formato = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Erro ao preparar a consulta: ' . $conn->error]);
    exit;
}

$stmt->bind_param('s', $formato);
$stmt->execute();
$result = $stmt->get_result();

$mm = [];
while ($row = $result->fetch_assoc()) {
    $mm[] = $row['MM'];
}

// Retorna os dados em formato JSON
echo json_encode($mm);

$stmt->close();
$conn->close();
?>

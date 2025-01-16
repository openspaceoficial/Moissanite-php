<?php
$conn = new mysqli('localhost', 'root', '', 'moissanite', 3306);

if ($conn->connect_error) {
    die("Erro na conexão com o banco de dados: " . $conn->connect_error);
}

// Valida o parâmetro 'pedra'
$pedra = $_GET['pedra'] ?? '';
if (!in_array($pedra, ['moissanite', 'zirconia'])) {
    echo json_encode(['error' => 'Tipo de pedra inválido.']);
    exit;
}

// Consulta segura
$sql = "SELECT DISTINCT Formato FROM $pedra";
$result = $conn->query($sql);

if (!$result) {
    echo json_encode(['error' => 'Erro na consulta: ' . $conn->error]);
    exit;
}

// Monta o array de formatos
$formatos = [];
while ($row = $result->fetch_assoc()) {
    $formatos[] = $row['Formato'];
}

// Retorna os dados em formato JSON
echo json_encode($formatos);

$conn->close();
?>

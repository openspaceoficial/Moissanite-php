<?php
$host = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'pedrasDB';
$porta = 3306;

// Conexão com o banco de dados
$conn = new mysqli($host, $usuario, $senha, $banco, $porta);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verifica se o método da requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pedra = $_POST['pedra'] ?? '';
    $formato = $_POST['formato'] ?? '';
    $mm = $_POST['mm'] ?? '';
    $estoqueUnidade = isset($_POST['estoqueUnidade']) ? (int)$_POST['estoqueUnidade'] : 0;
    $estoqueQuilate = isset($_POST['estoqueQuilate']) ? (int)$_POST['estoqueQuilate'] : 0;

    // Validação da tabela
    if (!in_array($pedra, ['moissanite', 'zirconia'])) {
        echo "Erro: Tipo de pedra inválido.";
        exit;
    }

    // Validação de campos obrigatórios
    if (empty($formato) || empty($mm)) {
        echo "Erro: Formato e MM são obrigatórios.";
        exit;
    }

    // Atualização no banco de dados
    $sql = "UPDATE $pedra SET Estoque_Unidade = ?, Estoque_Quilate = ? WHERE Formato = ? AND MM = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo "Erro ao preparar a consulta: " . $conn->error;
        exit;
    }

    $stmt->bind_param('iiss', $estoqueUnidade, $estoqueQuilate, $formato, $mm);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo "Estoque atualizado com sucesso!";
        } else {
            echo "Nenhuma linha foi alterada. Verifique se os valores informados existem no banco de dados.";
        }
    } else {
        echo "Erro ao atualizar estoque: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

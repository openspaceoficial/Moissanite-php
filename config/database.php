<?php
// Configurações do banco de dados (se necessário)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moissainite";

// Conectar ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}
?>

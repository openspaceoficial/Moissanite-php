<?php
$host = 'localhost';    // Endereço do servidor
$dbname = 'pedrasdb';   // Nome do banco de dados
$user = 'root';         // Usuário do banco de dados
$password = '';         // Senha do banco de dados (deixe vazio se não houver senha)

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco: " . $e->getMessage();
    exit;
}
?>



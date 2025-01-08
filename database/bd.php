
<?php 
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "u566100020_moissanite"; 
// Conectar ao banco de dados usando MySQLi
$conn = new mysqli($servername, $username, $password, $dbname); 
// Verificar conexão 
if ($conn->connect_error) { die("Conexão falhou: " . $conn->connect_error); }
?> 

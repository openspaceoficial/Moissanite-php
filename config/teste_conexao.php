<?php
include('database.php'); // Caminho correto para o arquivo no mesmo diretório
if ($conn) {
    echo "Conexão bem-sucedida!";
} else {
    echo "Erro na conexão!";
}
?>
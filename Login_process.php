<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include('./database/database.php'); // Inclua corretamente o arquivo de configuração do banco

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Exibe os dados do formulário
    var_dump($_POST);

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verificar no banco de dados se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Exibe os resultados da consulta
    var_dump($result);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verificar a senha
        if (password_verify($senha, $user['senha'])) {
            // Definir o tipo de usuário baseado no banco de dados
            $_SESSION['user_type'] = $user['tipo_usuario']; 

            // Redirecionar para o painel do cliente ou administrador
            if ($user['tipo_usuario'] == 'cliente') {
                header("Location: index.php");
                exit();
            } elseif ($user['tipo_usuario'] == 'admin') {
                header("Location: admin_dashboard.php");
                exit();
            }
        } else {
            echo "Usuário ou senha incorretos!";
        }
    } else {
        // Verifica se o botão "Entrar como Visitante" foi clicado
        if (isset($_POST['visitor'])) {
            $_SESSION['user_type'] = 'guest'; // Define o tipo de usuário como visitante
            header("Location: index.php"); // Redireciona para o painel do cliente
            exit();
        } else {
            echo "Usuário ou senha incorretos!";
        }
    }
}

?>
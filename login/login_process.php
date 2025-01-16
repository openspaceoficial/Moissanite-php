<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include(__DIR__ . '/../config/database.php'); // Caminho ajustado para a conexão correta

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    // Verifica se o botão "Entrar como Visitante" foi clicado
    if (isset($_POST['visitor'])) {
        $_SESSION['user_type'] = 'guest'; // Define o tipo de usuário como visitante
        header("Location: ../orcamento/orcamento.php"); // Redireciona para o painel do cliente
        exit();
    }

    // Valida os campos
    if (empty($email) || empty($senha)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    try {
        // Consulta ao banco para verificar o usuário
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se o usuário existe
        if ($user) {
            // Verifica a senha
            if (password_verify($senha, $user['senha'])) {
                // Define a sessão com base no tipo de usuário
                $_SESSION['user_type'] = $user['tipo_usuario'];
                $_SESSION['usuario_id'] = $user['id'];

                // Redireciona para o painel correspondente
                if ($user['tipo_usuario'] == 'cliente') {
                    header("Location: ../orcamento/orcamento.php");
                } elseif ($user['tipo_usuario'] == 'admin') {
                    header("Location: ../restrita/area_restrita.php");
                }
                exit();
            } else {
                echo "Usuário ou senha incorretos!";
            }
        } else {
            echo "Usuário ou senha incorretos!";
        }
    } catch (PDOException $e) {
        echo "Erro ao acessar o banco de dados: " . $e->getMessage();
    }
}
?>

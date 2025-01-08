<?php
session_start();
include('./database/database.php'); // Certifique-se de que o arquivo de configuração do banco está correto

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupera o e-mail do formulário
    $email = $_POST['email'];

    // Verificar se o campo de e-mail não está vazio
    if (!empty($email)) {
        // Prepara a consulta SQL para buscar o usuário com o e-mail fornecido
        $query = "SELECT * FROM usuarios WHERE email = ?";
        
        if ($stmt = $conn->prepare($query)) {
            // Vincula o parâmetro de entrada à consulta SQL
            $stmt->bind_param("s", $email);
            
            // Executa a consulta
            $stmt->execute();
            
            // Obtém o resultado da consulta
            $result = $stmt->get_result();
            
            // Verifica se o usuário foi encontrado
            if ($result->num_rows > 0) {
                // Aqui, você pode gerar um link de recuperação de senha ou algo do tipo
                echo "E-mail encontrado! Enviar link para redefinir a senha.";
                // Implementar o envio de e-mail para recuperação de senha
            } else {
                echo "E-mail não encontrado!";
            }

            // Fecha a declaração
            $stmt->close();
        } else {
            echo "Erro ao preparar a consulta: " . $conn->error;
        }
    } else {
        echo "Por favor, insira seu e-mail!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueceu a Senha</title>
    <style>
        /* Resetando margens e padding */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }

        .form-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
            width: 100%;
            max-width: 400px;
        }

        .form-container h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            text-align: center;
            color: #3A6332;
        }

        input[type="email"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            outline: none;
            box-sizing: border-box;
        }

        input[type="email"]:focus {
            border-color: #3A6332;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3A6332;
            color: white;
            font-size: 1.2rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #5e9154;
        }

        .form-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #3A6332;
            font-size: 1rem;
            text-decoration: none;
        }

        .form-container a:hover {
            text-decoration: underline;
        }

        footer {
            background-color: #70B068;
            text-align: center;
            padding: 20px;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        footer .redes i {
            font-size: 1.5rem;
            margin-right: 10px;
        }

        footer .redes a {
            color: #fff;
            margin-right: 15px;
        }

        footer .redes i:hover {
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Esqueceu a Senha</h1>
        <form action="esqueceu_senha.php" method="POST">
            <input type="email" name="email" placeholder="Digite seu e-mail" required>
            <button type="submit">Enviar</button>
        </form>

        <!-- Novo botão para voltar ao login -->
        <button onclick="window.location.href='login.php';">Voltar para o Login</button>
    </div>

    <footer>
        <div class="redes">
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-whatsapp"></i></a>
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
        </div>
    </footer>

    <script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>
</body>
</html>
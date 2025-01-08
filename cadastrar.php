<?php
session_start();  // Certifique-se de iniciar a sessão no início

include('./database/database.php'); // Certifique-se de que o arquivo de configuração do banco está correto

// Credenciais fixas de administrador
$admin_email = "admin@romulo.com";
$admin_senha = "admin123";

$admin_email2 = "admin@bruna.com";
$admin_senha2 = "123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se é um cadastro
    if (isset($_POST['cadastro'])) {
        // Código de cadastro permanece o mesmo
    }

    // Verifica se é um login
    if (isset($_POST['email']) && isset($_POST['senha']) && !isset($_POST['cadastro'])) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        // Verificação para administradores
        if (($email === $admin_email && $senha === $admin_senha) || ($email === $admin_email2 && $senha === $admin_senha2)) {
            $_SESSION['usuario_id'] = 0;
            $_SESSION['role'] = 'admin';

            header("Location: dashboard.php");
            exit;
        } else {
            // Verificação para clientes
            $query = "SELECT * FROM usuarios WHERE email = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $usuario = $result->fetch_assoc();
                
                // Debug: verificar se o usuário foi recuperado
                var_dump($usuario);
                
                // Verifique a senha
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['role'] = $usuario['role'];

                    // Debug: verificar a variável de sessão
                    var_dump($_SESSION);

                    if ($_SESSION['role'] === 'admin') {
                        header("Location: dashboard.php");
                    } else {
                        // Redireciona para o painel do cliente
                        header("Location: index.php");
                    }
                    exit;
                } else {
                    echo "<script>alert('Senha incorreta!');</script>";
                }
            } else {
                echo "<script>alert('Usuário não encontrado!');</script>";
            }
        }
    }

    // Acesso como visitante
    if (isset($_POST['visitor'])) {
        $_SESSION['role'] = 'visitor';
        header("Location: index.php");
        exit;
    }
}
?>




<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romulo Moissanite - Login e Cadastro</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Lexend", sans-serif;
        }

        main {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f4f4f4;
        }

        .form-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #3A6332;
        }

        input {
            background-color: #eee;
            border: none;
            border-radius: 5px;
            margin: 10px 0;
            padding: 10px 15px;
            width: 100%;
            font-size: 1rem;
        }

        button {
            background-color: #3A6332;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 1rem;
            width: 100%;
        }

        button:hover {
            background-color: #5e9154;
        }

        a {
            color: #3A6332;
            text-decoration: none;
            margin-top: 10px;
            font-size: 0.9rem;
        }

        footer {
            background-color: #70B068;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }

        /* Estilo para esconder o formulário de cadastro inicialmente */
        .cadastro-form {
            display: none;
        }

    </style>
</head>

<body>
    <main>
        <div class="form-container">
            <!-- Formulário de Login -->
            <form action="login.php" method="POST">
    <h1>Entrar</h1>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="senha" placeholder="Senha" required>
    <a href="esqueceu_senha.php">Esqueceu sua senha?</a> 
    <button type="submit">Entrar</button>
</form>


            <!-- Botão Entrar como Visitante -->
            <form action="login.php" method="POST">
                <button type="submit" name="visitor" value="true">Entrar como Visitante</button>
            </form>

            <!-- Botão para Exibir o Formulário de Cadastro -->
            <button type="button" onclick="toggleCadastro()">Cadastrar</button>

            <!-- Formulário de Cadastro -->
            <form action="login.php" method="POST" class="cadastro-form" id="cadastroForm">
                <h1>Cadastre-se</h1>
                <input type="text" name="nome" placeholder="Nome" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <input type="text" name="telefone" placeholder="Telefone" required>
                <input type="date" name="data_nascimento" required>
                <input type="text" name="endereco" placeholder="Endereço" required>
                <input type="text" name="cep" placeholder="CEP" required>
                <input type="text" name="cidade" placeholder="Cidade" required>
                <button type="submit" name="cadastro" value="true">Cadastrar</button>
            </form>
        </div>
    </main>

    <footer>
        <p>Romulo Moissanite &copy; 2025</p>
    </footer>

    <script>
        // Função para alternar a exibição do formulário de cadastro
        function toggleCadastro() {
            var cadastroForm = document.getElementById("cadastroForm");
            if (cadastroForm.style.display === "none" || cadastroForm.style.display === "") {
                cadastroForm.style.display = "block";
            } else {
                cadastroForm.style.display = "none";
            }
        }
    </script>
</body>

</html>
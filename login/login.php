<?php
session_start();  // Certifique-se de iniciar a sessão no início

include('../config/database.php'); // Certifique-se de que o arquivo de configuração do banco está correto

// Credenciais fixas de administrador
$admin_email = "admin@romulo.com";
$admin_senha = "admin123";

$admin_email2 = "admin@bruna.com";
$admin_senha2 = "123";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Verifica se é um cadastro
    if (isset($_POST['cadastro'])) {
        // Recupera os dados do formulário
        $nome = trim($_POST['nome']);
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);
        $telefone = trim($_POST['telefone']);
        $data_nascimento = $_POST['data_nascimento'];
        $endereco = trim($_POST['endereco']);
        $cep = trim($_POST['cep']);
        $cidade = trim($_POST['cidade']);
    
        // Validação de email único
        $query = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            echo "<script>alert('Este email já está cadastrado!');</script>";
        } else {
            // Criptografa a senha
            $senha_hash = password_hash($senha, PASSWORD_BCRYPT);
    
            // Insere os dados no banco de dados
            $query = "INSERT INTO usuarios (nome, email, senha, telefone, data_nascimento, endereco, cep, cidade, role) 
                      VALUES (:nome, :email, :senha, :telefone, :data_nascimento, :endereco, :cep, :cidade, 'cliente')";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':senha', $senha_hash, PDO::PARAM_STR);
            $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindValue(':data_nascimento', $data_nascimento, PDO::PARAM_STR);
            $stmt->bindValue(':endereco', $endereco, PDO::PARAM_STR);
            $stmt->bindValue(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindValue(':cidade', $cidade, PDO::PARAM_STR);
    
            if ($stmt->execute()) {
                // Usuário cadastrado com sucesso
                $_SESSION['usuario_id'] = $conn->lastInsertId(); // Captura o ID do novo usuário
                $_SESSION['role'] = 'cliente';
                header("Location: ../orcamento/orcamento.php");
                exit;
            } else {
                // Em caso de erro
                echo "<script>alert('Erro ao cadastrar usuário. Tente novamente.');</script>";
            }
        }
    }
    
    // Verifica se é um login
    if (isset($_POST['email']) && isset($_POST['senha']) && !isset($_POST['cadastro'])) {
        $email = trim($_POST['email']);
        $senha = trim($_POST['senha']);

        // Verificação para administradores
        if (($email === $admin_email && $senha === $admin_senha) || ($email === $admin_email2 && $senha === $admin_senha2)) {
            $_SESSION['usuario_id'] = 0;
            $_SESSION['role'] = 'admin';
            header("Location: ../restrita/area_restrita.php"); //caminho que adm vai depois de logar
            exit;
        } else {
            // Verificação para clientes
            $query = "SELECT * FROM usuarios WHERE email = :email";
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Verifique se o usuário existe
            if ($usuario) {
                // Adicione um var_dump para depurar, se necessário
                // var_dump($usuario);
            
                // Verifique a senha
                if (password_verify($senha, $usuario['senha'])) {
                    $_SESSION['usuario_id'] = $usuario['id'];
                    $_SESSION['role'] = $usuario['role'];
            
                    // Adicione var_dump para depuração da sessão, se necessário
                    // var_dump($_SESSION);
            
                    if ($_SESSION['role'] === 'admin') {
                        header("Location: ../restrita/area_restrita.php");
                    } else {
                        header("Location: ../orcamento/orcamento.php");
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
        header("Location: ../orcamento/orcamento.php");
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

body {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    position: relative;
    color: #fff;
    text-align: center;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: url('../imagens/img.jpg');
    background-size: cover;
    background-position: center;
    background-attachment: fixed;
    opacity: 0.5; /* Transparência do fundo */
    z-index: -1; /* Para garantir que o conteúdo fique sobre a imagem */
}

main {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
    padding: 20px;
    width: 100%;
    max-width: 500px;
}

.form-container {
    background-color: rgba(0, 0, 0, 0.6); /* Fundo translúcido para contrastar com a imagem */
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    padding: 40px;
    width: 100%;
    max-width: 450px;
    display: flex;
    flex-direction: column;
    align-items: center;
    opacity: 1;
    transition: opacity 0.5s ease;
}

h1 {
    font-size: 2.5rem;
    margin-bottom: 20px;
    color: #fff;
    text-align: center;
    font-weight: bold;
    text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.6); /* Sombra no título para maior destaque */
}

input,
button {
    background-color: rgba(255, 255, 255, 0.8);
    border: 1px solid #ddd;
    border-radius: 5px;
    margin: 12px 0;
    padding: 12px 18px;
    width: 100%;
    font-size: 1.2rem;
    transition: all 0.3s ease;
}

input:focus,
button:hover {
    background-color: #70b068;
    border-color: #3a6332;
    box-shadow: 0 0 10px rgba(58, 99, 50, 0.3);
    outline: none;
}

button {
    background-color: #3a6332;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #2f4f27;
}

a {
    color: #fff;
    text-decoration: none;
    margin-top: 10px;
    font-size: 1rem;
    transition: color 0.3s ease;
}

a:hover {
    color: #70b068;
}

footer {
    background-color: #3a6332;
    color: #fff;
    text-align: center;
    padding: 20px 0;
    width: 100%;
    margin-top: 20px;
    position: fixed;
    bottom: 0;
}

.cadastro-form {
    display: none;
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
}

.form-container {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    0% {
        transform: translateY(30px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}

.cadastro-form.show {
    display: block;
    opacity: 1;
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
        // Função para alternar a exibição do formulário de cadastro com animação
        function toggleCadastro() {
            var cadastroForm = document.getElementById("cadastroForm");
            cadastroForm.classList.toggle("show");
        }
    </script>
</body>

</html>

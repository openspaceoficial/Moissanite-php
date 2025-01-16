<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="./css/header.css">
</head>
<body>
<header>
        <div class="logo-header-class">
            <div class="container-logo-class">
                <a class="botao-index-home-header"href="index.php">
                    <p>Romulo
                        <span>Moissanite</span>
                    </p>
                </a>
            </div>
        </div>
        <div class="menu-header-class">
            <div class="cadastro-header-class">
                <div class="botao-header-cadastrar">
                    <input type="submit" value="Cadastro/Login" onclick="" >
                </div>
                <a class="botao-responsivo-cadastrar">Cadastro/Login</a>
            </div>
            <div class="menu-suspenso-header-class">
                <div class="menu-wrapper">
                    <!-- Ícone do menu suspenso -->
                    <div class="menu-icon" id="menu-toggle">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
      <div class="menu-aberto-header">
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="./restrita/area_restrita.php">Area Restrita</a></li>
            <li><a href="orcamento.php">Orçamento</a></li>
            <li><a href="estoque.php">Estoque</a></li>
        </ul>
      </div>  
    </header>
    <script>
            // Selecionando o ícone do menu e o menu
const menuToggle = document.getElementById("menu-toggle");
const menuHeader = document.querySelector(".menu-aberto-header");

// Adicionando o evento de clique
menuToggle.addEventListener("click", function () {
    menuHeader.classList.toggle("show"); // Alterna a visibilidade do menu
    menuToggle.classList.toggle("open"); // Adiciona a animação do ícone
});
    </script>
</body>
</html>


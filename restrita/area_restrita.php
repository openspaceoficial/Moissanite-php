<?php

if (isset($_SESSION['usuario'])): ?>
    <div class="botao-sair">
        <a href="logout.php" class="btn-sair">Sair</a>
    </div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área Restrita</title>
    <link rel="stylesheet" href="area.css">
</head>
<body>
    <header>
        <main>
            <h2>Romulo <span class="verde">Moissanite</span></h2>
            <div class="dropdown">
                <button class="dropbtn">Cadastro/Login</button>
                <div class="dropdown-content" id="dropdownMenu">
                    <p>Usuário</p>
                    <input type="text">
                    <p>Senha</p>
                    <input type="password">
                    <button>Entrar</button>
                </div>
            </div>

            <div class="menu-icon" id="menu-toggle">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <div class="menu-aberto-header">
                <ul>
                    <li><a href="./gerenciar/gerenciar_pedidos.php">Status Pedido</a></li>
                    <li><a href="area_restrita.php">Área Restrita</a></li>
                    <li><a href="./orcamento/orcamento.php">Orçamento</a></li>
                    <li><a href="../historico/historico.php">Histórico</a></li>
                    <li><a href="../login.php" class="btn-sair">Sair</a></li>
                </ul>
            </div>
        </main>
    </header>

    <main class="content">
        <div class="textos">
            <h1>Área Restrita</h1>
        </div>
    </main>
    <section class="botoes">
        <div class="nomeE">
            <button class="est"></button>
            <p> <a href="valores.html">Valores</a></p>
        </div>
        <div class="nomeA">
            <button class="alt"></button>
            <p><a href="alterar.html">Alterar</a></p>
        </div>
        <div class="nomeH">
            <button class="His"></button>
            <p><a href="../historico/historico.php">Histórico</a></p>
        </div>
        <div class="nomeO">
            <button class="orcamento"></button>
            <p><a href="../orcamento.php">Orçamento</a></p>
        </div>
        <div class="nomeF">
            <button class="financas"></button>
            <p><a href="finanças/Finanças.php">Finanças</a></p>
        </div>
        
    </section>
    
    <footer>
        <div class="nome-footer">
            <h3>Romulo <span>Moissanite</span></h3>
        </div>
        <nav>
            <ul>
                <li><a href="../historico/historico.php">Histórico</a></li>
                <li><a href="/Fazer/fazerOrcamento.html">Orçamento</a></li>
                <li><a href="area.html">Área Restrita</a></li>
            </ul>
        </nav>
        <div class="redes">
            <i class="fa-brands fa-instagram" id="insta"></i>
            <i class="fa-brands fa-whatsapp"></i>
            <i class="fa-brands fa-facebook" style="color: #ffffff;"></i>
        </div>
    </footer>


<style>
   
</style>

<script>
    // Selecionando o ícone do menu e o menu
const menuToggle = document.getElementById("menu-toggle");
const menuHeader = document.querySelector(".menu-aberto-header");

// Adicionando o evento de clique
menuToggle.addEventListener("click", function () {
    menuHeader.classList.toggle("show"); // Alterna a visibilidade do menu
    menuToggle.classList.toggle("open"); // Adiciona a animação do ícone
});

//btn-cadastro
document.querySelector(".dropbtn").addEventListener("click", function () {
    const dropdown = document.querySelector(".dropdown");
    dropdown.classList.toggle("show");
});

// Fecha o dropdown se o usuário clicar fora dele
window.onclick = function (event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.querySelectorAll(".dropdown-content");
        dropdowns.forEach(function (dropdown) {
            if (dropdown.style.display === "block") {
                dropdown.style.display = "none";
            }
        });
    }
};


</script>
</body>
<script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>
</html>
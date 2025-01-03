<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Romulo Moissanite</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <header>
        <div class="logo-header-class">
            <div class="container-logo-class">
                <p>Romulo
                    <span>Moissanite</span>
                </p>
            </div>
        </div>
        <div class="menu-header-class">
            <div class="cadastro-header-class">
                <div class="botao-header-cadastrar">
                    <input type="submit" value="Cadastro/Login" onclick="" >
                </div>
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
            <li><a href="">Home</a></li>
            <li><a href="">Area Restrita</a></li>
            <li><a href="">Orçamento</a></li>
            <li><a href="">Estoque</a></li>
        </ul>
      </div>  

    </header>

    <div class="body">
        <div class="banner-conteudo">
            <div class="container-banner-conteudo">
                <div class="frase-destaque-banner-conteudo">Seja Bem-Vindo!</div>
                <div class="frase-normal-banner-conteudo">Clique no item desejado e faça seu pedido agora mesmo</div>
            </div>
        </div>
        <div class="imagens-clicaveis-conteudo">
            <div class="container-items-clicaveis-conteudo">
                <div class="historico-clicavel-conteudo"> <a href="">Histórico</a></div>
                <div class="historico-e-area-restrita-container-clicavel-conteudo">
                    <div class="orcamento-clicavel-conteudo"><a href="">Orçamento</a></div>
                    <div class="area-restrita-clicavel-conteudo"><a href="">Área Restrita</a></div>
                </div>
            </div>
        </div>
    </div>
    <footer></footer>

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

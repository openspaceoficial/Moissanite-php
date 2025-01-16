<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./historico.css">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Lexend:wght@100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="../area_restrita/area_restrita.php">Área Restrita</a></li>
                    <li><a href="/Fazer/fazerOrcamento.html">Orçamento</a></li>
                    <li><a href="../historico/historico.php">Histórico</a></li>
                </ul>
            </div>
        </main>
    </header>

    <main id="main">
        <div id="inicio">
            <h1>Histórico</h1>
            <p>Veja suas compras anteriores e coloque-as no seu orçamento novamente.</p>
        </div>

        <div class="card-1">
            <div class="name">
                <div class="name-rock">Moissanite</div>
            </div>
            <div class="table">
                <div class="table-divs">
                    <div>Formato</div>
                    <div>Milímetro</div>
                    <div>Unidade</div>
                    <div>Quilate</div>
                    <div>Valor</div>
                </div>


                <div id="productsContainerHistory">

                </div>

            </div>
            <div class="table-genered"></div>

            <button class="btnAdd">Adicionar ao orçamento</button>
        </div>


        <div class="card-2">
            <div class="name">
                <div class="name-rock">Zirconia</div>
            </div>

            <div class="table-divs">
                <div>Formato</div>
                <div>Milímetro</div>
                <div>Unidade</div>
                <div>Cor</div>
                <div>Valor</div>
            </div>

            <div id="productsContainerHistoryZirconia">

            </div>

            <button class="btnAdd">Adicionar ao orçamento</button>
        </div>

    </main>

    <footer>
        <div class="nome-footer">
            <h3>Romulo <span>Moissanite</span></h3>
        </div>
        <nav>
            <ul>
                <li><a href="">Histórico</a></li>
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

    <script>
      
    </script>
</body>
<script src="./historico.js"></script>
</html>
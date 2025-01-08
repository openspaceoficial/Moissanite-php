<?php
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php"); // Redireciona para o login se não for admin
    exit;
}
?>



<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo</title>
    <link rel="stylesheet" href="area.css">
</head>
<body>
<header>
        <main>
            <h2>Romulo <span class="verde">admin</span></h2>
            <div class="dropdown">
                <button class="dropbtn">Bem-vindo, <?php echo $_SESSION['username']; ?></button>
                <div class="dropdown-content" id="dropdownMenu">
                    <p>Usuário: <?php echo $_SESSION['username']; ?></p>
                    <a href="logout.php">Sair</a>
                </div>
            </div>

            <div class="menu-icon" onclick="toggleMenu()">
                <div class="bar"></div>
                <div class="bar"></div>
                <div class="bar"></div>
            </div>

            <div class="sidebar" id="sidebar">
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="dashboard.php">Área Restrita</a></li>
                    <li><a href="/Fazer/fazerOrcamento.html">Orçamento</a></li>
                    <li><a href="Historico.html">Histórico</a></li>
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
            <p> <a href="dashboard/valores.php">Valores</a></p>
        </div>
        <div class="nomeA">
            <button class="alt"></button>
            <p><a href="dashboard/alterar.php">Alterar</a></p>
        </div>
        <div class="nomeH">
            <button class="His"></button>
            <p><a href="dashboard/Historico.php">Histórico</a></p>
        </div>
        <div class="nomeO">
            <button class="orcamento"></button>
            <p><a href="dashboard/orcamento/fazerOrcamento.php">Orçamento</a></p>
        </div>
        <div class="nomeF">
            <button class="financas"></button>
            <p><a href="controle/controle.php">Finanças</a></p>
        </div>
    </section>

    <footer>
        <div class="nome-footer">
            <h3>Romulo <span>Moissanite</span></h3>
        </div>
        <nav>
            <ul>
                <li><a href="Historico.html">Histórico</a></li>
                <li><a href="/Fazer/fazerOrcamento.html">Orçamento</a></li>
                <li><a href="dashboard.php">Área Restrita</a></li>
            </ul>
        </nav>
        <div class="redes">
            <i class="fa-brands fa-instagram" id="insta"></i>
            <i class="fa-brands fa-whatsapp"></i>
            <i class="fa-brands fa-facebook" style="color: #ffffff;"></i>
        </div>
    </footer>

</body>
</html>

<?php
include '../database/bd.php';
// Consulta para buscar os valores de Frete e Desconto
$sql = "SELECT SUM(ValorFrete) AS TotalFrete, SUM(Desconto) AS TotalDesconto FROM orcamento";
$result = $conn->query($sql);

$totalFrete = 0;
$totalDesconto = 0;

// Consultas para buscar os preços de custo de Moissanite e Zirconia
$sqlMoissanite = "SELECT SUM(preco_custo) AS TotalMoissanite FROM moissanite";
$resultMoissanite = $conn->query($sqlMoissanite);
$totalMoissanite = 0;

if ($resultMoissanite->num_rows > 0) {
    $rowMoissanite = $resultMoissanite->fetch_assoc();
    $totalMoissanite = $rowMoissanite['TotalMoissanite'];
}

// Soma do total de Zirconia
$sqlZirconia = "SELECT SUM(preco_custo) AS TotalZirconia FROM zirconia";
$resultZirconia = $conn->query($sqlZirconia);
$totalZirconia = 0;

if ($resultZirconia->num_rows > 0) {
    $rowZirconia = $resultZirconia->fetch_assoc();
    $totalZirconia = $rowZirconia['TotalZirconia'];
}

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $totalFrete = $row['TotalFrete'];
    $totalDesconto = $row['TotalDesconto'];
}


// Calcular os totais
$somaFrete = $totalFrete;
$somaDesconto = $totalDesconto;

$somaFreteFormatado = number_format($somaFrete, 2, ',', '.');
$somaDescontoFormatado = number_format($somaDesconto, 2, ',', '.');
$totalMoissaniteFormatado = number_format($totalMoissanite, 2, ',', '.');
$totalZirconiaFormatado = number_format($totalZirconia, 2, ',', '.');

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Valores</title>
    <link rel="stylesheet" href="./valores.css">
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

    <div class="container">
        <h1 class="title">Valores</h1>
        <div class="card">
            <label for="frete">Frete:</label>
            <input type="text" id="frete" placeholder="Digite o valor do frete" value="R$ <?php echo htmlspecialchars($somaFreteFormatado); ?>" readonly>
        </div>
        <div class="card">
            <label for="desconto">Desconto:</label>
            <input type="text" id="desconto" placeholder="Digite o valor do desconto" value="R$ <?php echo htmlspecialchars($somaDescontoFormatado); ?>" readonly>
        </div>
        <div class="card">
            <label for="desconto">Moissanite:</label>
            <input type="text" id="desconto" placeholder="Digite o valor do desconto" value="R$ <?php echo htmlspecialchars($totalMoissaniteFormatado); ?>" readonly>
        </div>
        <div class="card">
            <label for="desconto">Zirconia:</label>
            <input type="text" id="desconto" placeholder="Digite o valor do desconto" value="R$ <?php echo htmlspecialchars($totalZirconiaFormatado); ?>" readonly>
        </div>
    </div>

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
</body>
<script src="./valores.js"></script>
<script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>

</html>
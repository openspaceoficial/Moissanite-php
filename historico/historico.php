<?php
// Conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pedrasDB"; // Substitua pelo nome do seu banco

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Query para buscar dados da tabela
$sql = "SELECT TipoPedra, FormatoPedra, MM, CT, QuantidadeUnidade, ValorPedra FROM orcamento";
$result = $conn->query($sql);

// Arrays para armazenar os dados filtrados
$moissaniteData = [];
$zirconiaData = [];

// Filtragem dos dados por tipo de pedra (ignora case)
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        if  (strcasecmp($row['TipoPedra'], 'Zircônia') === 0) {
            $zirconiaData[] = $row;

        } elseif (strcasecmp($row['TipoPedra'], 'moissanite') === 0) {
            $moissaniteData[] = $row;
        }
    }
} else {
    echo "Nenhum dado encontrado na tabela.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico</title>
    <link rel="stylesheet" href="./historicos.css">
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

    <main>
        <h1>Histórico</h1>
        <p>Veja suas compras anteriores e coloque-as no seu orçamento novamente.</p>

        <!-- Card Moissanite -->
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
                    <?php if (!empty($moissaniteData)): ?>
                        <?php foreach ($moissaniteData as $item): ?>
                            <div class="table-row">
                                <div><?= htmlspecialchars($item['FormatoPedra']) ?></div>
                                <div><?= htmlspecialchars($item['MM']) ?> mm</div>
                                <div><?= htmlspecialchars($item['QuantidadeUnidade']) ?> un</div>
                                <div><?= htmlspecialchars($item['CT']) ?> ct</div>
                                <div>R$ <?= number_format($item['ValorPedra'], 2, ',', '.') ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="table-row">Nenhum dado encontrado.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Card Zirconia -->
        <div class="card-2">
            <div class="name">
                <div class="name-rock">Zirconia</div>
            </div>
            <div class="table">
                <div class="table-divs">
                    <div>Formato</div>
                    <div>Milímetro</div>
                    <div>Unidade</div>
                    <div>Quilate</div>
                    <div>Valor</div>
                </div>
                <div id="productsContainerHistoryZirconia">
                    <?php if (!empty($zirconiaData)): ?>
                        <?php foreach ($zirconiaData as $item): ?>
                            <div class="table-row">
                                <div><?= htmlspecialchars($item['FormatoPedra']) ?></div>
                                <div><?= htmlspecialchars($item['MM']) ?> mm</div>
                                <div><?= htmlspecialchars($item['QuantidadeUnidade']) ?> un</div>
                                <div><?= htmlspecialchars($item['CT']) ?> ct</div>
                                <div>R$ <?= number_format($item['ValorPedra'], 2, ',', '.') ?></div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="table-row">Nenhum dado encontrado.</div>
                    <?php endif; ?>
                </div>
            </div>
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
</body>
<script src="./historico.js"></script>
<script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>
</html>
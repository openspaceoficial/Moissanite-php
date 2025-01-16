<?php
include '../../config/database.php';

// Configurações de paginação
$limite = 15; // Número de registros por página
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $limite;

// Consulta com limite e offset
$sql = "SELECT 'moissanite' AS Pedra, Formato, CT, MM, Qpl, Ideal, Estoque_Unidade, Estoque_Quilate, Fator, Preco_Venda FROM moissanite
        UNION
        SELECT 'zirconia', Formato, CT, MM, Qpl, Ideal, Estoque_Unidade, Estoque_Quilate, Fator, Preco_Venda FROM zirconia
        LIMIT :limite OFFSET :offset";

$stmt = $conn->prepare($sql);
$stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Total de registros
$totalSql = "SELECT (SELECT COUNT(*) FROM moissanite) + (SELECT COUNT(*) FROM zirconia) AS total";
$totalResultado = $conn->query($totalSql)->fetch(PDO::FETCH_ASSOC);
$totalRegistros = $totalResultado['total'];
$totalPaginas = ceil($totalRegistros / $limite);

// Buscar tipos de pedras para o formulário
$pedras = ['moissanite', 'zirconia'];
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque</title>
    <link rel="stylesheet" href="../../css/alterar-estoque.css">
    <link rel="stylesheet" href="../../css/estoque.css">
    <link rel="stylesheet" href="../../css/filtro-estoque.css">
    <link rel="stylesheet" href="../../css/tabela-estoque.css">
    <script src="./js/carregarFormatos.js" defer></script>
    <script src="./js/alterarEstoque.js" defer></script>
    <script src="./js/carregarMM.js" defer></script>
    <script src="./js/carregarFormatosConsulta.js" defer></script>
    <script src="./js/carregarMMConsulta.js" defer></script>
    <script src="./js/filtrarTabelaConsulta.js" defer></script>
</head>
<body>
    <h1>Estoque</h1>
    <div class="container-filtro-tabela">
        <h2 class="consulta-pedras-p">Consulta de Pedras</h2>
        <div class="container-filtros">
            <label for="pedraConsulta">Tipo de Pedra (Consulta):</label>
            <select id="pedraConsulta" name="pedraConsulta" onchange="carregarFormatosConsulta()">
                <option value="">Selecione a Pedra</option>
                <?php foreach ($pedras as $pedra): ?>
                    <option value="<?= htmlspecialchars($pedra) ?>"><?= ucfirst(htmlspecialchars($pedra)) ?></option>
                <?php endforeach; ?>
            </select>

            <label for="formatoConsulta">Formato (Consulta):</label>
            <select id="formatoConsulta" name="formatoConsulta" onchange="carregarMMConsulta()">
                <option value="">Todos</option>
            </select>

            <label for="mmConsulta">MM (Consulta):</label>
            <select id="mmConsulta" name="mmConsulta">
                <option value="">Todos</option>
            </select>

            <button type="button" onclick="filtrarTabelaConsulta()">Aplicar Filtros</button>
        </div>

        <div class="container-tabela">
            <table id="tabelaConsulta">
                <thead>
                    <tr>
                        <th>Pedra</th>
                        <th>Formato</th>
                        <th>CT</th>
                        <th>MM</th>
                        <th>Qpl</th>
                        <th>Ideal</th>
                        <th>Estoque_Unidade</th>
                        <th>Estoque_Quilate</th>
                        <th>Fator</th>
                        <th>Preço de Venda</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultado as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['Pedra']) ?></td>
                        <td><?= htmlspecialchars($row['Formato']) ?></td>
                        <td><?= htmlspecialchars($row['CT']) ?></td>
                        <td><?= htmlspecialchars($row['MM']) ?></td>
                        <td><?= htmlspecialchars($row['Qpl']) ?></td>
                        <td><?= htmlspecialchars($row['Ideal']) ?></td>
                        <td><?= htmlspecialchars($row['Estoque_Unidade']) ?></td>
                        <td><?= htmlspecialchars($row['Estoque_Quilate']) ?></td>
                        <td><?= htmlspecialchars($row['Fator']) ?></td>
                        <td><?= htmlspecialchars($row['Preco_Venda']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="pagination">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>">&laquo; Anterior</a>
        <?php endif; ?>
        <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>">Próxima &raquo;</a>
        <?php endif; ?>
    </div>

    <h2 class="p-alterar-pedra">Editar Informações</h2>
    <form onsubmit="event.preventDefault(); alterarEstoque();">
        <label for="pedra">Tipo de Pedra:</label>
        <select id="pedra" name="pedra" onchange="carregarFormatos()" required>
            <option value="">Selecione a Pedra</option>
            <?php foreach ($pedras as $pedra): ?>
                <option value="<?= htmlspecialchars($pedra) ?>"><?= ucfirst(htmlspecialchars($pedra)) ?></option>
            <?php endforeach; ?>
        </select>

        <label for="formato">Formato:</label>
        <select id="formato" name="formato" onchange="carregarMM()" required>
            <option value="">Selecione o Formato</option>
        </select>

        <label for="mm">MM:</label>
        <select id="mm" name="mm" required>
            <option value="">Selecione o MM</option>
        </select>

        <label for="estoqueUnidade">Estoque Unidade:</label>
        <input type="number" id="estoqueUnidade" name="estoqueUnidade" required>

        <label for="estoqueQuilate">Estoque Quilate:</label>
        <input type="number" id="estoqueQuilate" name="estoqueQuilate" required>

        <button type="submit">Alterar</button>
    </form>
</body>
</html>

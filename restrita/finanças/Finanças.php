<?php
include '../../config/database.php';

// Inicialize as variáveis para evitar warnings
$fatu_Moi = 0;
$comp_Moi = 0;
$fatu_Zir = 0;
$comp_Zir = 0;
$somaFaturamento = 0;
$somaCompras = 0;
$despesasTotais = 0;
$valorLiquido = 0;

// Inicialize as variáveis para evitar avisos
$registroParaEditar = null;
$exibirTabela = false;

// Função para carregar os dados do banco de dados com filtros aplicados
function carregarDados($conn, $filtro)
{
    $sql = "SELECT * FROM lancamentos WHERE 1=1"; // Base da consulta
    $params = [];

    // Aplicar filtros dinamicamente
    if (!empty($filtro['nome'])) {
        $sql .= " AND Nome LIKE ?";
        $params[] = "%" . $filtro['nome'] . "%";
    }
    if (!empty($filtro['assunto'])) {
        $sql .= " AND Assunto LIKE ?";
        $params[] = "%" . $filtro['assunto'] . "%";
    }
    if (!empty($filtro['pagamentos'])) {
        $sql .= " AND pagamentos = ?";
        $params[] = $filtro['pagamentos'];
    }
    if (!empty($filtro['data'])) {
        $sql .= " AND Data_de_lancamento = ?";
        $params[] = $filtro['data'];
    }
    if (!empty($filtro['valor'])) {
        $sql .= " AND Valor = ?";
        $params[] = $filtro['valor'];
    }

    // Preparar e executar a consulta
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
    }

    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os dados como array associativo
}

// Função para buscar um registro específico para edição
function buscarRegistro($conn, $id)
{
    $sql = "SELECT * FROM lancamentos WHERE Id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
    }

    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Função para adicionar novos registros
if (isset($_POST['adicionar'])) {
    $tipo = $_POST['tipo'] ?? '';
    $nome = $_POST['nome'] ?? '';
    $assunto = $_POST['assunto'] ?? '';
    $parcela = $_POST['parcelas'] ?? 1;
    $data = $_POST['data'] ?? '';
    $pagamentos = $_POST['pagamentos'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $precoParcelas = ($parcela != 0) ? $preco / $parcela : 0;

    $sql = "INSERT INTO lancamentos (Tipo, Nome, Assunto, Data_de_lancamento, pagamentos, Valor, Valor_Parcela, Parcelas) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
    }

    $stmt->execute([$tipo, $nome, $assunto, $data, $pagamentos, $preco, $precoParcelas, $parcela]);

    echo "<script>alert('Valores adicionados com sucesso!');</script>";
}


    if (isset($_POST['filtrar'])) {
        $filtro['nome'] = $_POST['filtro_nome'] ?? '';
        $filtro['assunto'] = $_POST['filtro_assunto'] ?? '';
        $filtro['pagamentos'] = $_POST['filtro_pagamentos'] ?? '';
        $filtro['data'] = $_POST['filtro_data'] ?? '';
        $filtro['valor'] = $_POST['filtro_valor'] ?? '';
        $exibirTabela = true;
    }

    if (isset($_POST['editar'])) {
        $id = $_POST['id'] ?? -1;
        $registroParaEditar = buscarRegistro($conn, $id);
    }
    if (isset($_POST['atualizar'])) {
        $id = $_POST['id'] ?? -1;
        $tipo = $_POST['tipo'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $assunto = $_POST['assunto'] ?? '';
        $parcela = $_POST['parcelas'] ?? 1;
        $data = $_POST['data'] ?? '';
        $pagamentos = $_POST['pagamentos'] ?? '';
        $preco = $_POST['preco'] ?? 0;
        $precoParcelas = ($parcela != 0) ? $preco / $parcela : 0;
    
        $sql = "UPDATE lancamentos SET Tipo = ?, Nome = ?, Assunto = ?, Data_de_lancamento = ?, pagamentos = ?, Valor = ?, Valor_Parcela = ?, Parcelas = ? WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
        }
    
        $stmt->execute([$tipo, $nome, $assunto, $data, $pagamentos, $preco, $precoParcelas, $parcela, $id]);
    
        echo "<script>alert('Valores atualizados com sucesso!');</script>";
    }
    

    if (isset($_POST['excluir'])) {
        $id = $_POST['id'] ?? -1;
        $sql = "DELETE FROM lancamentos WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
        }
    
        $stmt->execute([$id]);
    
        echo "<script>alert('Registro excluído com sucesso!');</script>";
    }
    

    if (isset($_POST['marcarPago'])) {
        $id = $_POST['id'] ?? -1;
        $sql = "UPDATE lancamentos SET pagamentos = 'Pago' WHERE Id = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da consulta: " . implode(", ", $conn->errorInfo()));
        }
    
        $stmt->execute([$id]);
    
        echo "<script>alert('Registro marcado como Pago!');</script>";
    }
    

    if (isset($_POST['mostrarTabela'])) {
        $exibirTabela = true;
    }

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finanças</title>
    <script src="https://kit.fontawesome.com/5553e94d09.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="./finanças.css">
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

    <!-- Formulário de Adicionar ou Editar -->
    <div class="form-container">
        <!-- Formulário 1 -->
        <form method="POST" class="editarForm1">
            <h2><?php echo $registroParaEditar ? "Editar Valores" : "Adicionar Valores"; ?></h2>
            <input type="hidden" name="id" value="<?php echo $registroParaEditar['Id'] ?? ''; ?>">
            <input type="text" name="tipo" placeholder="Tipo" value="<?php echo $registroParaEditar['Tipo'] ?? ''; ?>" required>
            <input type="text" name="nome" placeholder="Nome" value="<?php echo $registroParaEditar['Nome'] ?? ''; ?>" required>
            <input type="text" name="assunto" placeholder="Assunto" value="<?php echo $registroParaEditar['Assunto'] ?? ''; ?>" required>
            <input type="number" name="parcelas" placeholder="Parcelas" value="<?php echo $registroParaEditar['Parcelas'] ?? ''; ?>" required>
            <input type="date" name="data" value="<?php echo $registroParaEditar['Data_de_lancamento'] ?? ''; ?>" required>
            <div class="radio-container">
                <input type="radio" id="pago" name="pagamentos" value="Pago"
                    <?php echo (isset($registroParaEditar['pagamentos']) && $registroParaEditar['pagamentos'] === 'Pago') ? 'checked' : ''; ?>>
                <label for="pago">Pago</label>

                <input type="radio" id="nao-pago" name="pagamentos" value="Não pago"
                    <?php echo (isset($registroParaEditar['pagamentos']) && $registroParaEditar['pagamentos'] === 'Não pago') ? 'checked' : ''; ?>>
                <label for="nao-pago">Não Pago</label>
            </div>
            <input type="number" name="preco" placeholder="Preço" step="0.01" value="<?php echo $registroParaEditar['Valor'] ?? ''; ?>" required>
            <button type="submit" name="<?php echo $registroParaEditar ? "atualizar" : "adicionar"; ?>">
                <?php echo $registroParaEditar ? "Atualizar" : "Adicionar"; ?>
            </button>
        </form>

        <!-- Formulário 2 -->
        <form method="POST" class="editarForm2">
            <h2>Filtrar Registros</h2>
            <input type="text" name="filtro_nome" placeholder="Nome">
            <input type="text" name="filtro_assunto" placeholder="Assunto">
            <select name="filtro_pagamentos">
                <option value="">Todos os Status</option>
                <option value="Pago">Pago</option>
                <option value="Não pago">Não Pago</option>
                <option value="Concluido">Concluído</option>
            </select>
            <input type="date" name="filtro_data">
            <input type="number" name="filtro_valor" placeholder="Valor">
            <button type="submit" name="filtrar">Filtrar</button>
        </form>
    </div>



    <!-- Botões para mostrar ou ocultar a tabela -->
    <form method="POST" class="botoes">
        <button type="submit" name="mostrarTabela" class="mostrar">Mostrar Tabela</button>
        <button type="button" onclick="ocultarTabelaSeparado()" class="ocultar">Ocultar Tabela</button>
    </form>


    <!-- Tabela de Dados -->
    <!-- Exibição da Tabela com os Dados -->
    <?php if ($exibirTabela): ?>
        <div id="tabela">
            <h2>Dados da Tabela</h2>
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nome</th>
                    <th>Assunto</th>
                    <th>Parcelas</th>
                    <th>Data</th>
                    <th>Pagamentos</th>
                    <th>Preço</th>
                    <th>Preço por Parcela</th>
                    <th>Ação</th>
                </tr>
                <?php
                $dados = carregarDados($conn, $filtro ?? null);
                if (count($dados) > 0) {
                    foreach ($dados as $dado) {
                        echo "<tr>
                                <td>{$dado['Id']}</td>
                                <td>{$dado['Tipo']}</td>
                                <td>{$dado['Nome']}</td>
                                <td>{$dado['Assunto']}</td>
                                <td>{$dado['Parcelas']}</td>
                                <td>{$dado['Data_de_lancamento']}</td>
                                <td>{$dado['pagamentos']}</td>
                                <td>{$dado['Valor']}</td>
                                <td>{$dado['Valor_Parcela']}</td>
                                <td>
                                    <form method='POST' style='display:inline'>
                                        <input type='hidden' name='id' value='{$dado['Id']}'>
                                        <button type='submit' name='editar'>Editar</button>
                                        <button type='submit' name='excluir'>Excluir</button>
                                        <button type='submit' name='marcarPago'>Marcar como Pago</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='10'>Nenhum dado encontrado</td></tr>";
                }
                ?>
            </table>

            <?php
            if (count($dados) > 0) {
                foreach ($dados as $dado) {
                    echo "<div class='card'>
                            <div class='card-header'>ID: {$dado['Id']} | Tipo: {$dado['Tipo']}</div>
                            <div class='card-section'>
                                <strong>Informações Pessoais</strong>
                                <div>Nome: {$dado['Nome']}</div>
                                <div>Assunto: {$dado['Assunto']}</div>
                            </div>
                            <div class='card-section'>
                                <strong>Detalhes do Pagamento</strong>
                                <div>Parcelas: {$dado['Parcelas']}</div>
                                <div>Pagamentos: {$dado['pagamentos']}</div>
                                <div>Preço: R$ {$dado['Valor']}</div>
                                <div>Preço por Parcela: R$ {$dado['Valor_Parcela']}</div>
                            </div>
                            <div class='card-section'>
                                <strong>Data</strong>
                                <div>{$dado['Data_de_lancamento']}</div>
                            </div>
                            <div class='acoes'>
                                <form method='POST'>
                                    <input type='hidden' name='id' value='{$dado['Id']}'>
                                    <button type='submit' name='editar'>Editar</button>
                                    <button type='submit' name='excluir'>Excluir</button>
                                    <button type='submit' name='marcarPago'>Pago</button>
                                </form>
                            </div>
                        </div>";
                }
            } else {
                echo "<div style='text-align:center;'>Nenhum dado encontrado</div>";
            }
            ?>
        </div>

    <?php endif; ?>

    <div class="form-container2">
        <form method="POST" action="">
            <div style="display: flex; justify-content: space-between;">
                <div class="form-container2">
                    <form method="POST" action="">
                        <h3>Moissanite</h3>
                        <label>Faturamento:</label>
                        <input type="number" step="0.01" name="fatu_Moi" id="fatu-Moi" value="<?= number_format($fatu_Moi, 2, '.', '') ?>" readonly>
                        <br>
                        <label>Compras:</label>
                        <input type="number" step="0.01" name="comp_Moi" id="comp-Moi" value="<?= number_format($comp_Moi, 2, '.', '') ?>" readonly>
                        <br><br>

                        <h3>Zirconia</h3>
                        <label>Faturamento:</label>
                        <input type="number" step="0.01" name="fatu_Zir" id="fatu-Zir" value="<?= number_format($fatu_Zir, 2, '.', '') ?>" readonly>
                        <br>
                        <label>Compras:</label>
                        <input type="number" step="0.01" name="comp_Zir" id="comp-Zir" value="<?= number_format($comp_Zir, 2, '.', '') ?>" readonly>
                        <br><br>

                    </form>
                </div>

                <?php
                echo "
                     <div class='results'>
                         <p><strong>Faturamento total:</strong> R$" . number_format($somaFaturamento, 2, ',', '.') . "</p>
                         <p><strong>Compras totais:</strong> R$" . number_format($somaCompras, 2, ',', '.') . "</p>
                         <p class='highlight'><strong>Valor total:</strong> R$" . number_format($despesasTotais, 2, ',', '.') . "</p>
                         <p class='highlight'><strong>Total líquido:</strong> R$" . number_format($valorLiquido, 2, ',', '.') . "</p>
                     </div>
                 ";
                ?>
            </div>
        </form>
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
<script src="./finanças.js"></script>
</html>
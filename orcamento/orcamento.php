<?php
require '../config/database.php';

try {
    // Obtendo os tipos de pedras disponíveis
    $stmt = $conn->query("SELECT DISTINCT 'moissanite' AS pedra UNION SELECT DISTINCT 'zirconia' AS pedra");
    $pedras = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Processar o formulário apenas quando enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletando dados enviados pelo formulário
    $pedra = $_POST['pedra'] ?? '';
    $formato = $_POST['formato'] ?? '';
    $milimetros = $_POST['milimetros'] ?? '';
    $quilates = $_POST['quilates'] ?? null;
    $cor = $_POST['cor'] ?? null;
    $quantidade = $_POST['quantidade'] ?? '';
    $imagem = '../imagens/wpp.jpeg';

    // Exibir o resumo do orçamento
    exibirResumoOrcamento($pedra, $formato, $milimetros, $quilates, $cor, $quantidade, $imagem);
    exit; // Garante que nada mais será enviado após o resumo
}

function exibirResumoOrcamento($pedra, $formato, $milimetros, $quilates, $cor, $quantidade, $imagem) {
    echo '
    <div class="formulario-orcamento-exibicao">
        <div class="container-formulario-exibicao-orcamento">
            <h2>Resumo do Orçamento:</h2>
            <p><strong>Tipo de Pedra:</strong> ' . htmlspecialchars($pedra) . '</p>
            <p><strong>Formato:</strong> ' . htmlspecialchars($formato) . '</p>
            <p><strong>Tamanho:</strong> ' . htmlspecialchars($milimetros) . '</p>
            <p><strong>Quantidade:</strong> ' . htmlspecialchars($quantidade) . ' unidade(s)</p>
            <img class="imagem-temporaria" src="' . htmlspecialchars($imagem) . '" alt="Imagem do Produto">
        </div>
    </div>';
}
$milimetros = $_POST['milimetros'] ?? null; // Garante que $milimetros está definido

if ($milimetros !== null && $milimetros >= 0.70 && $milimetros <= 3.00) {
    $tipoCompra = $_POST['tipoCompra'] ?? null;
    if (!in_array($tipoCompra, ['quilate', 'unidade'])) {
        die('Tipo de compra inválido.');
    }
} else {
    $tipoCompra = 'unidade'; // Define como "unidade" caso milímetros não estejam no intervalo
}


?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento de Pedras</title>
    <link rel="stylesheet" href="orcma.css">
    <link rel="stylesheet" href="../css/header.css">
    <script src="../js/carregarFormatos.js"></script>
    <script src="../js/carregarMM.js"></script>

    <script>
function carregarFormatos() {
    const pedra = document.getElementById('pedra');
    if (!pedra) {
        console.error('Elemento "pedra" não encontrado.');
        return;
    }

    fetch(`../php/carregar_formatos.php?pedra=${encodeURIComponent(pedra.value)}`)
        .then(response => {
            if (!response.ok) throw new Error('Erro na resposta do servidor.');
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            const formatoSelect = document.getElementById('formato');
            formatoSelect.innerHTML = '<option value="">Selecione o Formato</option>';
            data.forEach(formato => {
                const option = document.createElement('option');
                option.value = formato;
                option.textContent = formato;
                formatoSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Erro ao carregar formatos:', error));
}

function carregarMM() {
    const pedra = document.getElementById('pedra');
    const formato = document.getElementById('formato');
    if (!pedra || !formato) {
        console.error('Elementos "pedra" ou "formato" não encontrados.');
        return;
    }

    fetch(`../php/carregar_mm.php?pedra=${encodeURIComponent(pedra.value)}&formato=${encodeURIComponent(formato.value)}`)
        .then(response => {
            if (!response.ok) throw new Error('Erro na resposta do servidor.');
            return response.json();
        })
        .then(data => {
            const mmSelect = document.getElementById('milimetros');
            if (!mmSelect) {
                console.error('Elemento "milimetros" não encontrado.');
                return;
            }
            mmSelect.innerHTML = '<option value="">Selecione os Milímetros</option>';
            data.forEach(mm => {
                const option = document.createElement('option');
                option.value = mm;
                option.textContent = mm;
                mmSelect.appendChild(option);
            });

            // Verifica se o tamanho está entre 0,70 e 3,00MM
            mmSelect.addEventListener('change', function () {
                const selectedMM = parseFloat(mmSelect.value);
                const tipoCompra = document.getElementById('tipoCompra');
                const labelTipoCompra = document.getElementById('labelTipoCompra');

                if (selectedMM >= 0.70 && selectedMM <= 3.00) {
                    tipoCompra.style.display = 'block';
                    labelTipoCompra.style.display = 'block';
                } else {
                    tipoCompra.style.display = 'none';
                    labelTipoCompra.style.display = 'none';
                }
            });
        })
        .catch(error => console.error('Erro ao carregar milímetros:', error));
}


function enviarFormulario(form) {
    const formData = new FormData(form);

    fetch('../orcamento/orcamento.php', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok) throw new Error('Erro ao processar o formulário.');
            return response.text();
        })
        .then(data => {
            console.log('Resposta do servidor:', data); // Verifique o conteúdo retornado no console
            const resumoContainer = document.getElementById('resumoContainer');
            resumoContainer.innerHTML = ''; // Limpa o container
            resumoContainer.innerHTML = data; // Insere o novo conteúdo
            resumoContainer.style.display = 'block'; // Exibe o container de resumo
        })
        .catch(error => console.error('Erro ao enviar o formulário:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    const btnVerPrevia = document.getElementById('verPrevia');
    if (btnVerPrevia) {
        btnVerPrevia.addEventListener('click', function (event) {
            event.preventDefault(); // Evita o comportamento padrão do botão
            const form = document.querySelector('form');
            enviarFormulario(form);
        });
    } else {
        console.error('Botão "Ver Prévia" não encontrado.');
    }
});

function exibirFormularioResumo() {
    const formCadastro = document.querySelector('.container-forms-formulario-cadastro-orcamento');
    const resumoContainer = document.getElementById('resumoContainer');

    if (!formCadastro || !resumoContainer) {
        console.error('Elementos necessários não encontrados.');
        return;
    }

    resumoContainer.style.display = 'flex';
    formCadastro.classList.add('animar-slide-esquerda');
}

</script>


</head>
<body>
    <?php 
    include'../components/header.php'
    ?>

    <div class="body">
        <div class="formulario-orcamento-cadastro">
            <div class="container-formulario-cadastro-orcamento">
                <div class="container-h1-fomulario-cadastro-orcamento">
                    <h1 class="titulo-body-orcamento">Faça já seu orçamento!</h1>
                </div>
                <div class="container-h2-formulario-cadastro-orcamento">
                    <h2>Forneça as especificações do produto, veja a prévia e adicione-o ao seu orçamento.</h2>
                </div>

                <div class="container-formularios">
                    <!-- Formulário de Cadastro -->
                    <div class="container-forms-formulario-cadastro-orcamento">
                        <form method="POST">
                            <label for="pedra">Tipo de Pedra:</label>
                            <select id="pedra" name="pedra" onchange="carregarFormatos()" required>
                                <option value="">Selecione a Pedra</option>
                                <?php foreach ($pedras as $pedra): ?>
                                    <option value="<?= htmlspecialchars($pedra['pedra']) ?>"><?= ucfirst($pedra['pedra']) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <label for="formato">Formato:</label>
                            <select id="formato" name="formato" onchange="carregarMM()" required>
                                <option value="">Selecione o Formato</option>
                            </select>

                            <label for="milimetros">Escolha os milímetros:</label>
                            <select id="milimetros" name="milimetros" required>
                                <option value="">Selecione</option>
                            </select>

                            <div id="cor" style="display: none;">
                                <label for="cor">Escolha a cor:</label>
                                <select id="corInput" name="cor">
                                    <option value="">Selecione</option>
                                    <option value="Azul">Azul</option>
                                    <option value="Vermelho">Vermelho</option>
                                </select>
                            </div>

                            <label for="quantidade">Quantidade:</label>
                            <input type="number" id="quantidade" name="quantidade" min="1" required>

                            <button type="button" class="" id="verPrevia">Ver Prévia</button>
<button type="submit" class="a-forms" formaction="../carrinho/adicionar_ao_carrinho.php">Adicionar ao Carrinho</button>

                        </form>
                    </div>

                    <div id="resumoContainer"></div></div></div>
        </div>
    </div>

    <?php 
    include '../components/footer.php';
    ?>
</body>
</html>

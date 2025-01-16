<?php
session_start();


// Função para calcular o total dos itens no carrinho
function calcularTotal($carrinho) {
    $total = 0;
    foreach ($carrinho as $item) {
        $precoBase = ($item['pedra'] === 'moissanite') ? 50 : 30; // Simulação de preços
        $total += $precoBase * $item['quantidade'];
    }
    return $total;
}

// Função para calcular o desconto baseado no valor total
function calcularDesconto($total) {
    if ($total > 10000) return 20; // 20%
    if ($total > 7500) return 15; // 15%
    if ($total > 5000) return 13; // 13%
    if ($total > 3000) return 11; // 11%
    if ($total > 2500) return 9;  // 9%
    if ($total > 2000) return 7;  // 7%
    if ($total > 1500) return 5;  // 5%
    return 0; // Sem desconto
}

// Calcula o total do carrinho
$totalCarrinho = calcularTotal($_SESSION['carrinho']);
$descontoPercentual = calcularDesconto($totalCarrinho);
$valorDesconto = ($descontoPercentual / 100) * $totalCarrinho;
$totalComDesconto = $totalCarrinho - $valorDesconto;
$seguro = 0.0195 * $totalComDesconto; // 1,95% de seguro

// Recupera o frete calculado e garante que seja numérico
$frete = isset($_POST['frete']) ? (float) $_POST['frete'] : 0;
// Calcula o valor final
$totalAPagar = $totalComDesconto + $seguro + $frete;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Sanitiza o nome
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); // Valida o email
    $metodoPagamento = filter_input(INPUT_POST, 'metodo_pagamento', FILTER_SANITIZE_FULL_SPECIAL_CHARS); // Substitui FILTER_SANITIZE_STRING

    if (!$nome || !$email || !$metodoPagamento) {
        $erro = "Por favor, preencha todos os campos corretamente.";
    } else {
        echo "<p style='color: green;'>Compra realizada com sucesso! Obrigado, $nome.</p>";
        session_destroy(); // Destroi a sessão após a compra
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmar Pagamento</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="./finalizar_compra.css">
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
            <li><a href="area-restrita.php">Area Restrita</a></li>
            <li><a href="orcamento.php">Orçamento</a></li>
            <li><a href="estoque.php">Estoque</a></li>
        </ul>
      </div>  
    </header>
    <div class="container">
        <h2>Confirmar Pagamento</h2>

        <h3>Resumo do Pedido</h3>
        <table>
            <thead>
                <tr>
                    <th>Pedra</th>
                    <th>Formato</th>
                    <th>Tamanho (mm)</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['carrinho'] as $item): ?>
                    <tr>
                        <td><?php echo ucfirst($item['pedra']); ?></td>
                        <td><?php echo ucfirst($item['formato']); ?></td>
                        <td><?php echo $item['tamanho']; ?></td>
                        <td><?php echo $item['quantidade']; ?></td>
                        <td>R$<?php echo ($item['pedra'] === 'moissanite') ? '50.00' : '30.00'; ?></td>
                        <td>R$<?php echo ($item['pedra'] === 'moissanite' ? 50 : 30) * $item['quantidade']; ?>.00</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        
        <h3>Informações de Pagamento</h3>
        <label for="nome">Nome Completo:</label>
        <input type="text" name="nome" id="nome" required><br><br>

        <label for="email">E-mail:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="cpf">CPF:</label>
        <input type="text" name="cpf" id="cpf" pattern="\\d{11}" required><br><br>

            <div id="freteForm">
            <label for="cep">CEP:</label>
            <input type="text" name="cep" id="cep" placeholder="Digite o CEP" maxlength="8" required>
            <button type="button" id="calcularFrete">Calcular Frete</button>
            <div id="spinner"></div>

                <div id="freteOpcoes" style="margin-top: 15px; display: none;">
                    <h4>Selecione o Tipo de Frete:</h4>
                    <button type="button" class="opcao-frete" data-valor="15.00" data-tipo="PAC">PAC - R$ 15,00</button>
                    <button type="button" class="opcao-frete" data-valor="25.00" data-tipo="SEDEX">SEDEX - R$ 25,00</button>
                </div>
            </div>

            <p id="valorFreteSelecionado" style="font-weight: bold; margin-top: 15px;"></p>

            <label for="metodo_pagamento">Método de Pagamento:</label>
            <select name="metodo_pagamento" id="paymentMethod" required>
            <option value="">Selecione...</option>
            <option value="pix">Pix</option>
            <option value="boleto">Boleto Bancário</option>
            <option value="cartao">Cartão de Crédito</option>
            </select>
            <br><br>


<div class="resumo-final">
    <p>Total dos itens: <span class="valor">R$<?php echo number_format($totalCarrinho, 2, ',', '.'); ?></span></p>
    <p>Frete: <span id="freteValor" class="valor">R$<?php echo number_format($frete, 2, ',', '.'); ?></span></p>
    <p>
        Desconto: 
        <span class="tooltip">
            -<?php echo $descontoPercentual; ?>% 
            (R$<?php echo number_format($valorDesconto, 2, ',', '.'); ?>)
            <span class="tooltiptext">
                ACIMA DE 1500,00 → 5%<br>
                ACIMA DE 2000,00 → 7%<br>
                ACIMA DE 2500,00 → 9%<br>
                ACIMA DE 3000,00 → 11%<br>
                ACIMA DE 5000,00 → 13%<br>
                ACIMA DE 7500,00 → 15%<br>
                ACIMA DE 10000,00 → 20%
            </span>
        </span>
    </p>
    <p>Total com Desconto: <span class="valor">R$<?php echo number_format($totalComDesconto, 2, ',', '.'); ?></span></p>
    <p>Seguro (1,95%): <span class="valor">R$<?php echo number_format($seguro, 2, ',', '.'); ?></span></p>
    <p style="font-size: 1.2em; color: #e53e3e; font-weight: bold;">
        Total a Pagar: <span id="totalAPagar" class="valor">R$<?php echo number_format($totalAPagar, 2, ',', '.'); ?></span>
    </p>
</div>


    

<!-- Formulário de Finalizar Compra -->
<form method="POST" action="registrar_pedido.php" onsubmit="this.querySelector('button').disabled = true;">
    <input type="hidden" name="frete" value="<?php echo $frete; ?>">
    <input type="hidden" name="total" value="<?php echo $totalAPagar; ?>">
    <input type="hidden" name="metodo_pagamento" value="Pix"> <!-- Exemplo de método de pagamento -->
    <button type="submit">Acompanhar Pedido</button>
</form>



<!-- Botão para imprimir a página -->
<button onclick="window.print()" style="margin-top: 20px;">Imprimir</button></div>
</div>
<footer>
        <div class="logo-header-class-footer">
            <div class="container-logo-class-footer">
                <p class="p-footer">Romulo
                    <span class="span-footer">Moissanite</span>
                </p>
            </div>
        </div>
        <div class="menu-footer">
            <div class="links-menu-footer">
                <ul>
                    <li><a href="historico.php">Histórico</a></li>
                    <li><a href="orcamento.php">Orçamento</a></li>
                    <li><a href="area-restrito.php">Area Restrita</a></li>
                </ul>
            </div>
            <div class="redes-sociais-menu-footer">
            <ul>
    <li>
        <a href="https://www.instagram.com/openspace.oficial/">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 50 50">
                <path fill="white" d="M 16 3 C 8.8324839 3 3 8.8324839 3 16 L 3 34 C 3 41.167516 8.8324839 47 16 47 L 34 47 C 41.167516 47 47 41.167516 47 34 L 47 16 C 47 8.8324839 41.167516 3 34 3 L 16 3 z M 16 5 L 34 5 C 40.086484 5 45 9.9135161 45 16 L 45 34 C 45 40.086484 40.086484 45 34 45 L 16 45 C 9.9135161 45 5 40.086484 5 34 L 5 16 C 5 9.9135161 9.9135161 5 16 5 z M 37 11 A 2 2 0 0 0 35 13 A 2 2 0 0 0 37 15 A 2 2 0 0 0 39 13 A 2 2 0 0 0 37 11 z M 25 14 C 18.936712 14 14 18.936712 14 25 C 14 31.063288 18.936712 36 25 36 C 31.063288 36 36 31.063288 36 25 C 36 18.936712 31.063288 14 25 14 z M 25 16 C 29.982407 16 34 20.017593 34 25 C 34 29.982407 29.982407 34 25 34 C 20.017593 34 16 29.982407 16 25 C 16 20.017593 20.017593 16 25 16 z"></path>
            </svg>
        </a>
    </li>
    <li>
        <a href="">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 50 50">
                <path fill="white" d="M 25 2 C 12.309534 2 2 12.309534 2 25 C 2 29.079097 3.1186875 32.88588 4.984375 36.208984 L 2.0371094 46.730469 A 1.0001 1.0001 0 0 0 3.2402344 47.970703 L 14.210938 45.251953 C 17.434629 46.972929 21.092591 48 25 48 C 37.690466 48 48 37.690466 48 25 C 48 12.309534 37.690466 2 25 2 z M 25 4 C 36.609534 4 46 13.390466 46 25 C 46 36.609534 36.609534 46 25 46 C 21.278025 46 17.792121 45.029635 14.761719 43.333984 A 1.0001 1.0001 0 0 0 14.033203 43.236328 L 4.4257812 45.617188 L 7.0019531 36.425781 A 1.0001 1.0001 0 0 0 6.9023438 35.646484 C 5.0606869 32.523592 4 28.890107 4 25 C 4 13.390466 13.390466 4 25 4 z M 16.642578 13 C 16.001539 13 15.086045 13.23849 14.333984 14.048828 C 13.882268 14.535548 12 16.369511 12 19.59375 C 12 22.955271 14.331391 25.855848 14.613281 26.228516 L 14.615234 26.228516 L 14.615234 26.230469 C 14.588494 26.195329 14.973031 26.752191 15.486328 27.419922 C 15.999626 28.087653 16.717405 28.96464 17.619141 29.914062 C 19.422612 31.812909 21.958282 34.007419 25.105469 35.349609 C 26.554789 35.966779 27.698179 36.339417 28.564453 36.611328 C 30.169845 37.115426 31.632073 37.038799 32.730469 36.876953 C 33.55263 36.755876 34.456878 36.361114 35.351562 35.794922 C 36.246248 35.22873 37.12309 34.524722 37.509766 33.455078 C 37.786772 32.688244 37.927591 31.979598 37.978516 31.396484 C 38.003976 31.104927 38.007211 30.847602 37.988281 30.609375 C 37.969311 30.371148 37.989581 30.188664 37.767578 29.824219 C 37.302009 29.059804 36.774753 29.039853 36.224609 28.767578 C 35.918939 28.616297 35.048661 28.191329 34.175781 27.775391 C 33.303883 27.35992 32.54892 26.991953 32.083984 26.826172 C 31.790239 26.720488 31.431556 26.568352 30.914062 26.626953 C 30.396569 26.685553 29.88546 27.058933 29.587891 27.5 C 29.305837 27.918069 28.170387 29.258349 27.824219 29.652344 C 27.819619 29.649544 27.849659 29.663383 27.712891 29.595703 C 27.284761 29.383815 26.761157 29.203652 25.986328 28.794922 C 25.2115 28.386192 24.242255 27.782635 23.181641 26.847656 L 23.181641 26.845703 C 21.603029 25.455949 20.497272 23.711106 20.148438 23.125 C 20.171937 23.09704 20.145643 23.130901 20.195312 23.082031 L 20.197266 23.080078 C 20.553781 22.728924 20.869739 22.309521 21.136719 22.001953 C 21.515257 21.565866 21.68231 21.181437 21.863281 20.822266 C 22.223954 20.10644 22.02313 19.318742 21.814453 18.904297 L 21.814453 18.902344 C 21.828863 18.931014 21.701572 18.650157 21.564453 18.326172 C 21.426943 18.001263 21.251663 17.580039 21.064453 17.130859 C 20.690033 16.232501 20.272027 15.224912 20.023438 14.634766 L 20.023438 14.632812 C 19.730591 13.937684 19.334395 13.436908 18.816406 13.195312 C 18.298417 12.953717 17.840778 13.022402 17.822266 13.021484 L 17.820312 13.021484 C 17.450668 13.004432 17.045038 13 16.642578 13 z M 16.642578 15 C 17.028118 15 17.408214 15.004701 17.726562 15.019531 C 18.054056 15.035851 18.033687 15.037192 17.970703 15.007812 C 17.906713 14.977972 17.993533 14.968282 18.179688 15.410156 C 18.423098 15.98801 18.84317 16.999249 19.21875 17.900391 C 19.40654 18.350961 19.582292 18.773816 19.722656 19.105469 C 19.863021 19.437122 19.939077 19.622295 20.027344 19.798828 L 20.027344 19.800781 L 20.029297 19.802734 C 20.115837 19.973483 20.108185 19.864164 20.078125 19.923828 C 19.867096 20.342656 19.838461 20.445493 19.625 20.691406 C 19.29998 21.065838 18.968453 21.483404 18.792969 21.65625 C 18.639439 21.80707 18.36242 22.042032 18.189453 22.501953 C 18.016221 22.962578 18.097073 23.59457 18.375 24.066406 C 18.745032 24.6946 19.964406 26.679307 21.859375 28.347656 C 23.05276 29.399678 24.164563 30.095933 25.052734 30.564453 C 25.940906 31.032973 26.664301 31.306607 26.826172 31.386719 C 27.210549 31.576953 27.630655 31.72467 28.119141 31.666016 C 28.607627 31.607366 29.02878 31.310979 29.296875 31.007812 L 29.298828 31.005859 C 29.655629 30.601347 30.715848 29.390728 31.224609 28.644531 C 31.246169 28.652131 31.239109 28.646231 31.408203 28.707031 L 31.408203 28.708984 L 31.410156 28.708984 C 31.487356 28.736474 32.454286 29.169267 33.316406 29.580078 C 34.178526 29.990889 35.053561 30.417875 35.337891 30.558594 C 35.748225 30.761674 35.942113 30.893881 35.992188 30.894531 C 35.995572 30.982516 35.998992 31.07786 35.986328 31.222656 C 35.951258 31.624292 35.8439 32.180225 35.628906 32.775391 C 35.523582 33.066746 34.975018 33.667661 34.283203 34.105469 C 33.591388 34.543277 32.749338 34.852514 32.4375 34.898438 C 31.499896 35.036591 30.386672 35.087027 29.164062 34.703125 C 28.316336 34.437036 27.259305 34.092596 25.890625 33.509766 C 23.114812 32.325956 20.755591 30.311513 19.070312 28.537109 C 18.227674 27.649908 17.552562 26.824019 17.072266 26.199219 C 16.592866 25.575584 16.383528 25.251054 16.208984 25.021484 L 16.207031 25.019531 C 15.897202 24.609805 14 21.970851 14 19.59375 C 14 17.077989 15.168497 16.091436 15.800781 15.410156 C 16.132721 15.052495 16.495617 15 16.642578 15 z"></path>
            </svg>
        </a>
    </li>
    <li>
        <a href="">
            <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="33" height="33" viewBox="0 0 50 50">
                <path fill="white" d="M 25 3 C 12.861562 3 3 12.861562 3 25 C 3 36.019135 11.127533 45.138355 21.712891 46.728516 L 22.861328 46.902344 L 22.861328 29.566406 L 17.664062 29.566406 L 17.664062 26.046875 L 22.861328 26.046875 L 22.861328 21.373047 C 22.861328 18.494965 23.551973 16.599417 24.695312 15.410156 C 25.838652 14.220896 27.528004 13.621094 29.878906 13.621094 C 31.758714 13.621094 32.490022 13.734993 33.185547 13.820312 L 33.185547 16.701172 L 30.738281 16.701172 C 29.349697 16.701172 28.210449 17.475903 27.619141 18.507812 C 27.027832 19.539724 26.84375 20.771816 26.84375 22.027344 L 26.84375 26.044922 L 32.966797 26.044922 L 32.421875 29.564453 L 26.84375 29.564453 L 26.84375 46.929688 L 27.978516 46.775391 C 38.71434 45.319366 47 36.126845 47 25 C 47 12.861562 37.138438 3 25 3 z M 25 5 C 36.057562 5 45 13.942438 45 25 C 45 34.729791 38.035799 42.731796 28.84375 44.533203 L 28.84375 31.564453 L 34.136719 31.564453 L 35.298828 24.044922 L 28.84375 24.044922 L 28.84375 22.027344 C 28.84375 20.989871 29.033574 20.060293 29.353516 19.501953 C 29.673457 18.943614 29.981865 18.701172 30.738281 18.701172 L 35.185547 18.701172 L 35.185547 12.009766 L 34.318359 11.892578 C 33.718567 11.811418 32.349197 11.621094 29.878906 11.621094 C 27.175808 11.621094 24.855567 12.357448 23.253906 14.023438 C 21.652246 15.689426 20.861328 18.170128 20.861328 21.373047 L 20.861328 24.046875 L 15.664062 24.046875 L 15.664062 31.566406 L 20.861328 31.566406 L 20.861328 44.470703 C 11.816995 42.554813 5 34.624447 5 25 C 5 13.942438 13.942438 5 25 5 z"></path>
            </svg>
        </a>
    </li>
</ul>

            </div>
        </div>
    </footer>
    <script>
const menuToggle = document.getElementById("menu-toggle");
const menuHeader = document.querySelector(".menu-aberto-header");

// Adicionando o evento de clique
menuToggle.addEventListener("click", function () {
    menuHeader.classList.toggle("show"); // Alterna a visibilidade do menu
    menuToggle.classList.toggle("open"); // Adiciona a animação do ícone
});
document.getElementById('calcularFrete').addEventListener('click', function () {
    const cep = document.getElementById('cep').value;
    const spinner = document.getElementById('spinner');
    const freteOpcoes = document.getElementById('freteOpcoes');

    if (!cep || cep.length !== 8) {
        alert('Por favor, insira um CEP válido com 8 dígitos.');
        return;
    }

    spinner.style.display = 'inline-block';

    setTimeout(() => {
        spinner.style.display = 'none';
        freteOpcoes.style.display = 'block';
    }, 1500); // Simula carregamento do frete
});

document.querySelectorAll('.opcao-frete').forEach(button => {
    button.addEventListener('click', function () {
        const valorFrete = parseFloat(this.getAttribute('data-valor'));
        const tipoFrete = this.getAttribute('data-tipo');

        // Atualiza exibição do frete selecionado
        document.getElementById('freteValor').innerText = `R$ ${valorFrete.toFixed(2).replace('.', ',')}`;
        document.getElementById('valorFreteSelecionado').innerText = `Frete Selecionado: ${tipoFrete} - R$ ${valorFrete.toFixed(2).replace('.', ',')}`;

        // Calcula o novo total
        const totalComDesconto = parseFloat(<?php echo json_encode($totalComDesconto); ?>);
        const seguro = parseFloat(<?php echo json_encode($seguro); ?>);
        const novoTotal = totalComDesconto + seguro + valorFrete;

        // Atualiza o valor total a pagar na tela
        document.getElementById('totalAPagar').innerText = `R$ ${novoTotal.toFixed(2).replace('.', ',')}`;

        let inputFrete = document.querySelector('input[name="frete"]');
if (!inputFrete) {
    inputFrete = document.createElement('input');
    inputFrete.type = 'hidden';
    inputFrete.name = 'frete';
    document.querySelector('form').appendChild(inputFrete);
}
inputFrete.value = valorFrete; // Verifique se este valor está correto


        // Atualiza ou cria o campo oculto para o total
        let inputTotal = document.querySelector('input[name="total"]');
        if (!inputTotal) {
            inputTotal = document.createElement('input');
            inputTotal.type = 'hidden';
            inputTotal.name = 'total';
            document.querySelector('form').appendChild(inputTotal);
        }
        inputTotal.value = novoTotal.toFixed(2);
    });
});



document.getElementById('paymentMethod').addEventListener('change', function() {
    const metodoPagamento = this.value;
    

    const nome = document.getElementById('nome').value;
    const email = document.getElementById('email').value;
    const cpf = document.getElementById('cpf').value;
    const cep = document.getElementById('cep').value;

    // Recalcula o valor total com o frete
    const frete = parseFloat(document.querySelector('input[name="frete"]').value || 0);
    const totalComDesconto = <?php echo json_encode($totalComDesconto); ?>;
    const seguro = <?php echo json_encode($seguro); ?>;
    const totalAPagar = totalComDesconto + seguro + frete;
});
    </script>
</body>
</html>


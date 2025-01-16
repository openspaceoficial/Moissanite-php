<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Inicia a sessão apenas se ainda não estiver ativa
}

// Verifica se o índice foi enviado e se o carrinho existe
if (isset($_POST['indice']) && isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    $indice = intval($_POST['indice']);
    
    // Verifica se o índice é válido no array
    if (array_key_exists($indice, $_SESSION['carrinho'])) {
        // Remove o item do carrinho
        unset($_SESSION['carrinho'][$indice]);

        // Reindexa o array para corrigir os índices
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
}

// Redireciona de volta para a página do carrinho
header('Location: ../carrinho/adicionar_ao_carrinho.php');
exit();
?>

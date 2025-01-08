<?php
session_start();

// Verifica se o índice foi enviado e se a sessão 'carrinho' existe
if (isset($_POST['indice']) && isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
    $indice = $_POST['indice'];

    // Verifica se o índice é válido (existe no carrinho)
    if (isset($_SESSION['carrinho'][$indice])) {
        // Remove o item do carrinho
        unset($_SESSION['carrinho'][$indice]);

        // Reindexa o array para corrigir os índices
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']);
    }
}

// Redireciona de volta para a página do carrinho
header('Location: carrinho.php');
exit();
?>

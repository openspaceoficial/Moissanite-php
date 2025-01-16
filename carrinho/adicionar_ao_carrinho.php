<?php
session_start();

// Verifica se o formulário foi enviado corretamente
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coletar os dados do formulário
    $pedra = $_POST['pedra'] ?? '';
    $formato = $_POST['formato'] ?? '';
    $milimetros = $_POST['milimetros'] ?? '';
    $quilates = $_POST['quilates'] ?? null;
    $cor = $_POST['cor'] ?? null;
    $quantidade = $_POST['quantidade'] ?? '';
    $imagem = 'imagens/wpp.jpeg'; // Caminho da imagem

    // Monta o item do carrinho
    $item = [
        'imagem' => $imagem,
        'pedra' => $pedra,
        'formato' => $formato,
        'tamanho' => $milimetros,
        'quilates' => $quilates,
        'cor' => $cor,
        'quantidade' => $quantidade,
    ];

    // Inicializa o carrinho na sessão se ele não existir
    if (!isset($_SESSION['carrinho'])) {
        $_SESSION['carrinho'] = [];
    }

    // Adiciona o item ao carrinho
    $_SESSION['carrinho'][] = $item;

    // Redireciona para a página do carrinho
    header('Location: carrinho.php');
    exit;
}
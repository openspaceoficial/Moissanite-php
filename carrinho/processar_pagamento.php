<?php
require __DIR__ . '/vendor/autoload.php'; // Certifique-se de que o Mercado Pago SDK está instalado via Composer

use MercadoPago\SDK;
use MercadoPago\Preference;
use MercadoPago\Payer;

session_start();

// Configure suas credenciais do Mercado Pago
SDK::setAccessToken('TEST-1985454538422115-010908-43a0c9b88d0e1322893ee295f185153d-194484818    ');

// Dados do pagamento
$total = (float) $_POST['total'];
$nome = htmlspecialchars($_POST['nome']);
$email = htmlspecialchars($_POST['email']);
$metodo_pagamento = $_POST['metodo_pagamento'];

// Cria uma preferência de pagamento
$preference = new Preference();

// Define o pagador
$payer = new Payer();
$payer->name = $nome;
$payer->email = $email;

// Define os itens da compra
$item = new MercadoPago\Item();
$item->title = "Compra de Moissanite";
$item->quantity = 1;
$item->currency_id = "BRL";
$item->unit_price = $total;

$preference->items = [$item];
$preference->payer = $payer;

// Configura o redirecionamento após o pagamento
$preference->back_urls = [
    "success" => "http://localhost/seu_projeto/sucesso.php",
    "failure" => "http://localhost/seu_projeto/falha.php",
    "pending" => "http://localhost/seu_projeto/pendente.php"
];
$preference->auto_return = "approved";

// Salva a preferência
$preference->save();

// Redireciona para o Mercado Pago
header("Location: " . $preference->init_point);
exit;
?>

<?php
require_once('../vendor/autoload.php'); // Certifique-se de que o Composer está configurado

use GuzzleHttp\Client;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_cep = '31080255'; // CEP de origem fixo
    $to_cep = $_POST['cep']; // CEP de destino enviado pelo formulário

    if (empty($to_cep) || strlen($to_cep) !== 8) {
        echo json_encode(['error' => 'CEP inválido.']);
        exit;
    }

    $body = [
        'from' => ['postal_code' => $from_cep],
        'to' => ['postal_code' => $to_cep],
        'services' => '1,2,17',
        'options' => [
            'own_hand' => false,
            'receipt' => false,
            'insurance_value' => 0,
            'use_insurance_value' => false,
        ],
        'package' => [
            'height' => 16,
            'width' => 16,
            'length' => 16,
            'weight' => 1,
        ],
    ];

    $client = new Client();

    try {
        $response = $client->request('POST', 'https://sandbox.superfrete.com/api/v0/calculator', [
            'body' => json_encode($body),
            'headers' => [
                'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpYXQiOjE3MzYzNTc2MjUsInN1YiI6IkNtTTF4cFdSYzBUTkpjZzVYZTVOWU1EYlg2ejIifQ.3EfVl4jUZwFHHJczaW0OMm_mwgy5_t6bPX15U1fJeN8',
                'User-Agent' => 'MinhaAplicacao (romuloefamilia@msn.com)',
                'accept' => 'application/json',
                'content-type' => 'application/json',
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        if (isset($data['data']['shipping_options'])) {
            echo '<h3>Opções de Frete</h3>';
            foreach ($data['data']['shipping_options'] as $opcao) {
                echo '<form action="imprimir_etiqueta.php" method="POST">';
                echo '<p>';
                echo "Serviço: {$opcao['service_name']}<br>";
                echo "Valor: R$ " . number_format($opcao['price'], 2, ',', '.') . "<br>";
                echo "Prazo: {$opcao['delivery_time']} dias úteis<br>";
                echo '<input type="hidden" name="service_name" value="' . $opcao['service_name'] . '">';
                echo '<input type="hidden" name="price" value="' . $opcao['price'] . '">';
                echo '<input type="hidden" name="cep_destino" value="' . $to_cep . '">';
                echo '<button type="submit">Gerar Etiqueta</button>';
                echo '</p>';
                echo '</form>';
            }
        } else {
            echo 'Nenhuma opção de frete disponível.';
        }
    } catch (Exception $e) {
        echo 'Erro ao calcular o frete: ' . $e->getMessage();
    }
    exit;
}
?>

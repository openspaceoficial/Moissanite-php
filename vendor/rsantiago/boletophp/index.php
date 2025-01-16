<?php

include 'bootstrap.php';

use BoletoPhp\Model as Boleto;

$boleto = new Boleto\BoletoStrategy(new Boleto\Bradesco());

$boleto->getBoleto()->prepare(
  array(
    'nosso_numero' => '12525262', // Nosso numero sem o DV - REGRA: Máximo de 11 caracteres!
    'sacado' => 'Nome do seu Cliente',
    'endereco1' => 'Endereço do seu Cliente',
    'endereco2' => 'Cidade - Estado -  CEP: 00000-000',//'Itararé - SP - CEP: 18460-000',
    'demonstrativo1' => 'Pagamento de Compra na Loja Nonononono',
    'demonstrativo2' => 'Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$', // taxa_boleto
    'demonstrativo3' => 'BoletoPhp - http://www.boletophp.com.br',
    'instrucoes1' => '- Sr. Caixa, cobrar multa de 2% após o vencimento',
    'instrucoes2' => '- Receber até 10 dias após o vencimento',
    'instrucoes3' => '- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br',
    'instrucoes4' => '&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br',
    'quantidade' => '001',
    'aceite' => '',
    'especie' => 'R$',
    'especie_doc' => 'DS',
    'valor' => '1000,00',
    'prazo' => 2,
    'account' => array(
    	'agencia' => '1100',
	    'agencia_dv' => '0',
	    'conta' => '0102003',
	    'conta_dv' => '4',
	    'conta_cedente' => '0102003',
	    'conta_cedente_dv' => '4',
	    'carteira' => '06',
	    'identificacao' => 'BoletoPhp - Código Aberto de Sistema de Boletos',
	    'cpf_cnpj' => '12345678901',
	    'endereco' => 'Coloque o endereço da sua empresa aqui',
	    'cidade_uf' => 'Cidade / Estado',
	    'cedente' => 'Coloque a Razão Social da sua empresa aqui',
	    'carteira' => '06'
    )    
  )
);

$boleto->getBoleto()->output();
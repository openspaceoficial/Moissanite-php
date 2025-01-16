<?php

namespace BoletoPhp\Model;

class Itau extends Boleto {

  public function prepare($data) {

    try {

      parent::prepare($data);

      $this->setAccountData($data['account']);

    } catch (\Exception $e) {

      exit(json_encode(array('error' => 1, 'message' => $e->getMessage())));

    }

  }

  protected function setAccountData($accountData) {

    parent::setAccountData($accountData);

  }

  public function output() {

    $dadosboleto = $this->getData();
    $valor_cobrado = $dadosboleto['valor'];
    $dias_de_prazo_para_pagamento = $dadosboleto['prazo'];

    include(__DIR__ . '/../library/boleto_itau.php');
  }

}
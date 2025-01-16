<?php

namespace BoletoPhp\Model;

class BoletoStrategy {

  protected $code = array(
  );

  protected $billet = null;

  public function __construct(Billet $b) {

    $this->billet = $b;

  }

  public function getBoleto() {
    return $this->billet;
  }

}
<?php

namespace BoletoPhp\Model;

interface Billet {
  
  public function prepare($data);
  public function output();
}
<?php

namespace Jiad\SistemAtmSederhanaOop\Input;

class Input
{
  public static function input(string $data): string
  {
    echo $data;
    $fgets = fgets(STDIN);
    return trim($fgets);
  }
}

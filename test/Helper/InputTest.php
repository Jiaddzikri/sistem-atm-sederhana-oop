<?php

namespace Jiad\SistemAtmSederhanaOop\Input;

use Jiad\SistemAtmSederhanaOop\Input\Input;

require_once __DIR__ . "/../../app/Helper/Input.php";

class InputTest extends Input
{
}

$test = new InputTest();
$input = $test->input("Masukan Input: ");

echo $input . PHP_EOL;

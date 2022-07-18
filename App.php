<?php

use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\View\View;

require_once __DIR__ . "/app/View/View.php";
require_once __DIR__ . "/app/Database/Database.php";

Database::getConnection("prod");

$app = new View();
$app->view();

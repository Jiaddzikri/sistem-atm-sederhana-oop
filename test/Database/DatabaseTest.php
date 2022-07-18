<?php

namespace Jiad\SistemAtmSederhanaOop\Database;

use Jiad\SistemAtmSederhanaOop\Database\Database;

require_once __DIR__ . "/../../app/Database/Database.php";
require_once __DIR__ . "/../../app/Database/connection.php";

class DatabaseTest
{
  private \PDO $connection;

  public function __construct()
  {
    $this->connection = Database::getConnection();
  }

  public function testConnection(): void
  {
    if ($this->connection == null) {
      echo "Connection is null" . PHP_EOL;
      return;
    }

    echo "Connection is not null" . PHP_EOL;
  }
}

$database = new DatabaseTest();
$database->testConnection();

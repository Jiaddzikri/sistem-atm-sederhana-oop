<?php

namespace Jiad\SistemAtmSederhanaOop\Database;

use PDO;

class Database
{
  private static ?\PDO $connection = null;

  public static function getConnection($env = "test"): \PDO
  {
    if (self::$connection == null) {
      require_once __DIR__ . "/connection.php";
      $connection = getConnetion();
      self::$connection = new PDO(
        $connection[$env]["dsn"],
        $connection[$env]["username"],
        $connection[$env]["password"],
      );
    }
    return self::$connection;
  }
}

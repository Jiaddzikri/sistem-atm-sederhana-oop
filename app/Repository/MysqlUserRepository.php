<?php

namespace Jiad\SistemAtmSederhanaOop\Repository;

require_once __DIR__ . "/UserRepository.php";
require_once __DIR__ . "/../Domain/User.php";
require_once __DIR__ . "/../Database/Database.php";

use Jiad\SistemAtmSederhanaOop\Domain\User;
use Jiad\SistemAtmSederhanaOop\Repository\UserRepository;


class MysqlUserRepository implements UserRepository
{
  private \PDO $connection;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function save(User $user): User
  {
    $stmt = $this->connection->prepare("INSERT INTO user (no_kartu, pin, saldo) VALUES
    (?, ?, ?)");
    $stmt->execute([
      $user->no_kartu,
      $user->pin,
      $user->saldo
    ]);

    return $user;
  }

  public function deleteAll(): void
  {
    $this->connection->exec("DELETE FROM user");
  }

  public function findByNoKartu(string $request): ?User
  {
    $stmt = $this->connection->prepare("SELECT no_kartu, pin, saldo FROM user WHERE no_kartu = ?");
    $stmt->execute([
      $request
    ]);

    try {
      if ($row = $stmt->fetch()) {
        $user = new User();
        $user->no_kartu = $row["no_kartu"];
        $user->pin = $row["pin"];
        $user->saldo = $row["saldo"];

        return $user;
      } else {
        return null;
      }
    } finally {
      $stmt->closeCursor();
    }
  }

  public function updateSaldo(User $user): User
  {
    $stmt = $this->connection->prepare("UPDATE user SET saldo = ? WHERE no_kartu = ?");
    $stmt->execute([
      $user->saldo,
      $user->no_kartu
    ]);
    return $user;
  }
}

<?php

namespace Jiad\SistemAtmSederhanaOop\Repository;

require_once __DIR__  . "/../../app/Database/Database.php";
require_once __DIR__  . "/../../app/Repository/MysqlUserRepository.php";
require_once __DIR__  . "/../../app/Repository/UserRepository.php";


use Jiad\SistemAtmSederhanaOop\Domain\User;
use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\Repository\MysqlUserRepository;

class MyqslUserRepositoryTest
{
  private MysqlUserRepository $userRepository;

  public function __construct()
  {
    $connection = Database::getConnection();
    $this->userRepository = new MysqlUserRepository($connection);
  }

  public function testSaveAndFindAndDelete(): void
  {
    $user = new User();
    $user->no_kartu = "12345";
    $user->pin = "12345";
    $user->saldo = 0;

    $save = $this->userRepository->save($user);

    $find = $this->userRepository->findByNoKartu($save->no_kartu);

    $no_kartu = ($find->no_kartu == $save->no_kartu) ? "equals" : "not equals";
    $pin = ($find->pin == $save->pin) ? "equals" : "not equals";
    $saldo = ($find->saldo == $save->saldo) ? "equals" : "not equals";

    echo $no_kartu . PHP_EOL;
    echo $pin . PHP_EOL;
    echo $saldo . PHP_EOL;
  }

  public function __destruct()
  {
    $this->userRepository->deleteAll();
  }
}

$test = new MyqslUserRepositoryTest();
$test->testSaveAndFindAndDelete();

// All method on UserRepository is work;

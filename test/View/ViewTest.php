<?php

namespace Jiad\SistemAtmSederhanaOop\View;

use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\Domain\User;
use Jiad\SistemAtmSederhanaOop\Repository\MysqlUserRepository;
use Jiad\SistemAtmSederhanaOop\View\View;

require_once __DIR__ . "/../../app/Database/Database.php";
require_once __DIR__ . "/../../app/Helper/Input.php";
require_once __DIR__ . "/../../app/Model/IdentificationRequest.php";
require_once __DIR__ . "/../../app/Model/IdentificationResponse.php";
require_once __DIR__ . "/../../app/Repository/MysqlUserRepository.php";
require_once __DIR__ . "/../../app/Repository/UserRepository.php";
require_once __DIR__ . "/../../app/Service/UserService.php";
require_once __DIR__ . "/../../app/View/View.php";
require_once __DIR__ . "/../../app/Domain/User.php";

class ViewTest
{
  private View $view;
  private MysqlUserRepository $userRepository;

  public function __construct()
  {
    $this->view = new View();
    $connection = Database::getConnection();
    $this->userRepository = new MysqlUserRepository($connection);
  }

  public function testTransfer(): void
  {
    $user_1 = new User();
    $user_1->no_kartu = "12345";
    $user_1->pin = "12345";
    $user_1->saldo = 1000000;

    $user_2 = new User();
    $user_2->no_kartu = "54321";
    $user_2->pin = "54321";
    $user_2->saldo = 1000000;

    $this->userRepository->save($user_1);
    $this->userRepository->save($user_2);

    $this->view->view();
  }

  public function testPenarikan(): void
  {
    $user_1 = new User();
    $user_1->no_kartu = "12345";
    $user_1->pin = "12345";
    $user_1->saldo = 1000000;

    $this->userRepository->save($user_1);

    $this->view->view();
  }

  public function testSaldo(): void
  {
    $user_1 = new User();
    $user_1->no_kartu = "12345";
    $user_1->pin = "12345";
    $user_1->saldo = 1000000;

    $this->userRepository->save($user_1);

    $this->view->view();
  }

  public function __destruct()
  {
    $this->userRepository->deleteAll();
  }
}
//  transfer test
// $test = new ViewTest();
// $test->testTransfer();

// penarikan test
// $test = new ViewTest();
// $test->testPenarikan();

// test Infromasi Saldo
$test = new ViewTest();
$test->testPenarikan();

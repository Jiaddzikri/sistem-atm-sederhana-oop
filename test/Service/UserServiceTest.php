<?php

namespace Jiad\SistemAtmSederhanaOop\Service;

require_once __DIR__ . "/../../app/Database/Database.php";
require_once __DIR__ . "/../../app/Repository/MysqlUserRepository.php";
require_once __DIR__ . "/../../app/Repository/UserRepository.php";
require_once __DIR__ . "/../../app/Service/UserService.php";
require_once __DIR__ . "/../../app/Model/IdentificationRequest.php";
require_once __DIR__ . "/../../app/Model/IdentificationResponse.php";

use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\Domain\User;
use Jiad\SistemAtmSederhanaOop\Model\IdentificationRequest;
use Jiad\SistemAtmSederhanaOop\Service\UserService;
use Jiad\SistemAtmSederhanaOop\Repository\MysqlUserRepository;

class UserServiceTest
{
  private MysqlUserRepository $repository;
  private UserService $service;

  public function __construct()
  {
    $connection = Database::getConnection();
    $this->repository = new MysqlUserRepository($connection);
    $this->service = new UserService($this->repository);
  }

  public function testUserIdentification(): void
  {
    $user = new User();
    $user->no_kartu = "12345";
    $user->pin = "12345";
    $user->saldo = 0;

    $save = $this->repository->save($user);

    $request = new IdentificationRequest();
    $request->no_kartu = "12345";
    $request->pin = "12345";

    $validation = $this->service->identificationUser($request);

    $result = ($validation != null) ? "Login" : "Failed";

    echo $result;
  }

  public function __destruct()
  {
    $this->repository->deleteAll();
  }
}

$test = new UserServiceTest();
$test->testUserIdentification();

// validation method is worked;

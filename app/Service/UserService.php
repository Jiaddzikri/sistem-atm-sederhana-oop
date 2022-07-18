<?php

namespace Jiad\SistemAtmSederhanaOop\Service;

require_once __DIR__ . "/../Repository/UserRepository.php";
require_once __DIR__ . "/../Repository/MysqlUserRepository.php";
require_once __DIR__ . "/../Model/IdentificationRequest.php";
require_once __DIR__ . "/../Model/IdentificationResponse.php";
require_once __DIR__ . "/../Model/PenarikanRequest.php";
require_once __DIR__ . "/../Model/PenarikanResponse.php";

use Exception;
use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\Model\IdentificationRequest;
use Jiad\SistemAtmSederhanaOop\Model\IdentificationResponse;
use Jiad\SistemAtmSederhanaOop\Model\PenarikanRequest;
use Jiad\SistemAtmSederhanaOop\Model\PenarikanResponse;
use Jiad\SistemAtmSederhanaOop\Model\TransferRequest;
use Jiad\SistemAtmSederhanaOop\Model\TransferResponse;
use Jiad\SistemAtmSederhanaOop\Repository\MysqlUserRepository;

class UserService
{
  private MysqlUserRepository $userRepository;

  public function __construct($userRepository)
  {
    $this->userRepository = $userRepository;
  }

  public function identificationUser(IdentificationRequest $request): IdentificationResponse
  {
    $this->validationIdentificationUser($request);

    $validation = $this->userRepository->findByNoKartu($request->no_kartu);

    if ($validation == null) {
      throw new Exception("No Kartu tidak ditemukan");
    }

    if ($request->pin == $validation->pin) {
      $response = new IdentificationResponse();
      $response->user = $validation;
      return $response;
    }
    throw new Exception("Pin Salah");
  }

  private function validationIdentificationUser(IdentificationRequest $request): void
  {
    if ($request->no_kartu == null || trim($request->no_kartu) == "" || $request->pin == null || trim($request->pin) == "") {
      throw new Exception("Field No Kartu atau Pin Tidak Boleh Kosong");
    }
  }

  public function transfer(TransferRequest $request): ?TransferResponse
  {
    $this->validationTransfer($request);

    $findPengirim = $this->userRepository->findByNoKartu($request->no_kartu_pengirim);
    $findPenerima = $this->userRepository->findByNoKartu($request->no_kartu_penerima);

    try {
      Database::getConnection()->beginTransaction();

      if ($findPenerima == null) {
        throw new Exception("No kartu Penerima tidak ditemukan.");
      }

      $findPengirim->saldo -= $request->saldo;
      $findPenerima->saldo += $request->saldo;

      $update_pengirim = $this->userRepository->updateSaldo($findPengirim);
      $update_penerima = $this->userRepository->updateSaldo($findPenerima);

      Database::getConnection()->commit();

      $response = new TransferResponse();
      $response->user = $update_penerima;

      return $response;
    } catch (Exception $e) {
      Database::getConnection()->rollBack();
      throw new Exception;
    }
  }

  private function validationTransfer(TransferRequest $request): void
  {
    if ($request->no_kartu_pengirim == null || trim($request->no_kartu_pengirim) == "" || $request->no_kartu_penerima == null || trim($request->no_kartu_penerima) == "" || $request->saldo == null || trim($request->saldo) == "") {
      throw new Exception("Field Tidak Boleh Kosong");
    }

    if ($request->saldo < 50000) {
      throw new Exception("Saldo Minimal Rp. 50.000");
    } else if ($request->saldo > 2500000) {
      throw new Exception("Saldo Maksimal adalah Rp. 2.500.000");
    }
  }

  public function penarikan(PenarikanRequest $request): PenarikanResponse
  {
    $this->penarikanValidation($request);

    $find = $this->userRepository->findByNoKartu($request->no_kartu);

    try {
      Database::getConnection()->beginTransaction();

      if ($find == null) {
        throw new Exception("Nomor Kartu tidak ditemukan");
      } else if ($find->saldo < $request->saldo) {
        throw new Exception("Saldo anda tidak mencukupi");
      }

      var_dump($find->saldo -= $request->saldo);

      $update = $this->userRepository->updateSaldo($find);

      Database::getConnection()->commit();

      $response = new PenarikanResponse();
      $response->user = $update;

      return $response;
    } catch (Exception $e) {
      Database::getConnection()->rollBack();
      throw new Exception();
    }
  }

  private function penarikanValidation(PenarikanRequest $request): void
  {
    if ($request->no_kartu == null || trim($request->no_kartu) == "" || $request->saldo == null || trim($request->saldo) == "") {
      throw new Exception("No Kartu tidak boleh kosong");
    }
  }
}

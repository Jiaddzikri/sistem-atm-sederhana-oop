<?php

namespace Jiad\SistemAtmSederhanaOop\View;

require_once __DIR__ . "/../Database/Database.php";
require_once __DIR__ . "/../Repository/MysqlUserRepository.php";
require_once __DIR__ . "/../Repository/UserRepository.php";
require_once __DIR__ . "/../Helper/Input.php";
require_once __DIR__ . "/../Service/UserService.php";
require_once __DIR__ . "/../Model/TransferRequest.php";
require_once __DIR__ . "/../Model/TransferResponse.php";
require_once __DIR__ . "/../Model/IdentificationRequest.php";
require_once __DIR__ . "/../Model/IdentificationResponse.php";
require_once __DIR__ . "/../Model/TransferResponse.php";

use Exception;
use Jiad\SistemAtmSederhanaOop\Database\Database;
use Jiad\SistemAtmSederhanaOop\Input\Input;
use Jiad\SistemAtmSederhanaOop\Model\IdentificationRequest;
use Jiad\SistemAtmSederhanaOop\Model\IdentificationResponse;
use Jiad\SistemAtmSederhanaOop\Model\PenarikanRequest;
use Jiad\SistemAtmSederhanaOop\Model\PenarikanResponse;
use Jiad\SistemAtmSederhanaOop\Model\TransferRequest;
use Jiad\SistemAtmSederhanaOop\Model\TransferResponse;
use Jiad\SistemAtmSederhanaOop\Repository\MysqlUserRepository;
use Jiad\SistemAtmSederhanaOop\Repository\UserRepository;
use Jiad\SistemAtmSederhanaOop\Service\UserService;

class View
{
  private UserService $userService;
  private MysqlUserRepository $userRepository;

  public function __construct()
  {
    $connection = Database::getConnection();
    $this->userRepository = new MysqlUserRepository($connection);
    $this->userService = new UserService($this->userRepository);
  }

  public function view(): void
  {
    while (true) {
      echo "\n";
      echo "Selamat datang di Jiad's ATM" . PHP_EOL;
      echo "----------------------------------------";
      echo "\n";
      echo "Untuk membatalkan silahkan Ketik CTRL + C" . PHP_EOL;

      $identification = $this->identification();

      if ($identification != null) {
        $menu = $this->menu($identification->user->no_kartu, $identification->user->saldo);
        if ($menu == null) {
          break;
        }
      }
      continue;
    }
  }

  private function identification(): ?IdentificationResponse
  {
    echo "Silahkan Identifikasikan Akun anda Untuk Transaksi lebih lanjut" . PHP_EOL;
    echo "\n";


    try {
      $request = new IdentificationRequest();
      $request->no_kartu = Input::input("Masukan NO Kartu: ");

      $request->pin = Input::input("Masukan Pin: ");

      $identification = $this->userService->identificationUser($request);

      return $identification;
    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return null;
    }
  }

  private function menu(string $no_kartu, int $saldo): mixed
  {
    echo "\n";
    echo "Menu Transaksi" . PHP_EOL;
    echo "\n";

    echo "1. Transfer" . PHP_EOL;
    echo "2. Penarikan" . PHP_EOL;
    echo "3. Informasi Saldo" . PHP_EOL;
    echo "x. Batalkan Transaksi" . PHP_EOL;

    echo "\n";

    $menuInput = Input::input("Silahkan Pilih Opsi Yang Tersedia: ");

    if ($menuInput === "1") {
      $this->transfer($no_kartu);
    } else if ($menuInput === "2") {
      $this->penarikan($no_kartu);
    } else if ($menuInput === "3") {
      $this->informasiSaldo($saldo);
    } else if ($menuInput === "x") {
      return null;
    } else {
      echo "Perintah tidak diketahui" . PHP_EOL;
    }
    $confirmation = Input::input("Apakah Ingin Melanjutkan Transaksi? y untuk Ya, n untuk Tidak: ");

    if ($confirmation === "n") {
      return null;
    } else if ($confirmation === "y") {
      return !null;
    } else {
      echo "Perintah tidak diketahui" . PHP_EOL;
      return !null;
    }
  }

  private function transfer(string $no_kartu): ?TransferResponse
  {
    echo "\n";
    echo "Menu Transfer" . PHP_EOL;
    echo "\n";

    try {
      $request = new TransferRequest();
      $request->no_kartu_pengirim = $no_kartu;
      $request->no_kartu_penerima = Input::input("Masukan No Penerima: ");
      $request->saldo = Input::input("Masukan Jumlah Saldo: ");

      $transfer = $this->userService->transfer($request);

      if ($transfer != null) {
        echo "\n";
        echo "Transfer berhasil" . PHP_EOL;
        echo "\n";
        echo "Bukti Transfer" . PHP_EOL;
        echo "----------------------------------------";
        echo "\n";

        echo "Pengirim : " . $no_kartu . PHP_EOL;
        echo "Penerima : " . $transfer->user->no_kartu . PHP_EOL;
        echo "Jumlah Transfer : Rp. " . number_format($request->saldo) . PHP_EOL;

        echo "\n";
      }
      return $transfer;
    } catch (Exception $e) {
      echo $e->getMessage() . PHP_EOL;
      return null;
    }
  }

  private function penarikan(string $no_kartu): ?PenarikanResponse
  {
    echo "\n";
    echo "Menu Penarikan" . PHP_EOL;
    echo "\n";

    $saldo = Input::input("Masukan Jumlah Saldo: ");

    try {
      $request = new PenarikanRequest();
      $request->no_kartu = $no_kartu;
      $request->saldo = $saldo;

      $penarikan = $this->userService->penarikan($request);

      if ($penarikan != null) {
        echo "\n";
        echo "Penarikan Berhasil" . PHP_EOL;
        echo "\n";

        echo "Bukti Penarikan" . PHP_EOL;
        echo "Nomor Kartu: " . $penarikan->user->no_kartu . PHP_EOL;
        echo "Jumlah Penarikan: Rp. " . number_format($saldo) . PHP_EOL;
        echo "Sisa Saldo: Rp. " . number_format($penarikan->user->saldo) . PHP_EOL;

        echo "\n";
      }

      return $penarikan;
    } catch (Exception $e) {
      echo $e->getMessage();
      return null;
    }
  }

  private function informasiSaldo(int $saldo): void
  {
    echo "\n";
    echo "Menu Saldo" . PHP_EOL;
    echo "\n";

    echo "Jumlah Saldo anda: Rp. " . number_format($saldo) . PHP_EOL;

    echo "\n";
  }

  public function __destruct()
  {
    echo "Terima Kasih Telah Bertransaksi, sampai jumpa :)" . PHP_EOL;
  }
}

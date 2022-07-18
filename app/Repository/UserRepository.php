<?php

namespace Jiad\SistemAtmSederhanaOop\Repository;

use Jiad\SistemAtmSederhanaOop\Domain\User;

interface UserRepository
{
  public function findByNoKartu(string $request): ?User;
  public function updateSaldo(User $user): User;
}

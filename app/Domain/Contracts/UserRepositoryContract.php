<?php
namespace App\Domain\Contracts;

use App\Domain\Entities\User;

interface UserRepositoryContract {
    public function findByEmail(string $email): ?User;
}

<?php
namespace App\Domain\UseCases;

use App\Domain\Entities\UserFavorite;
use App\Infrastructure\Adapter\Out\Repositories\UserRepository;

class AddUserFavorite
{
    private UserRepository $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $gifID, int $userID, string $alias): UserFavorite|string
    {
        return $this->repository->addFavorite($gifID, $userID, $alias);
    }
}

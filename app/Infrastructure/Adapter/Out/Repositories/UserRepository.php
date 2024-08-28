<?php

namespace App\Infrastructure\Adapter\Out\Repositories;

use App\Domain\Entities\User;
use App\Domain\Contracts\UserRepositoryContract;
use App\Domain\Entities\UserFavorite;
use Illuminate\Support\Facades\Log;

final class UserRepository implements UserRepositoryContract
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User;
    }

    public function find(string $id): ?User
    {
        $user = $this->userModel->findOrFail($id);

        // Return Domain User model
        return new User(
            $user->name,
            $user->email,
            $user->email_verified_at,
            $user->password,
            $user->remember_token
        );
    }

    public function findByEmail(string $email): ?User
    {
        $user = $this->userModel
            ->where('email', $email)
            ->firstOrFail();

        // Return Domain User model
        return new User([
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password
        ]);
    }

    public function addFavorite(string $gifID, string $userID, $alias): UserFavorite|string {
        try {
            $userFavorite = new UserFavorite();
            $userFavorite->gif_id = $gifID;
            $userFavorite->user_id = $userID;
            $userFavorite->alias = $alias;

            $userFavorite->save();

            return $userFavorite;
        } catch (\Exception $ex) {
            Log::critical($ex->getMessage(), ["trace" => $ex->getTraceAsString()]);

            return $ex->getMessage();
        }
    }
}

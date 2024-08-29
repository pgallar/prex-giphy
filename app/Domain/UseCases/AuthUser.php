<?php
namespace App\Domain\UseCases;

use App\Infrastructure\Adapter\Out\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthUser
{
    private UserRepository $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Valida el usuario y contraseña enviados, genera el token del usuario en caso de ser válidos.
     *
     * @param string $email
     * @param string $password
     * @return array|mixed|string|null
     */
    public function execute(string $email, string $password): mixed
    {
        try {
            $user = $this->repository->findByEmail($email);
            if ($user && Hash::check($password, $user->getAuthPassword())) {
                $response = Http::post(env('APP_URL') . '/oauth/token', [
                    'grant_type' => 'password',
                    'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
                    'username' => $email,
                    'password' => $password,
                    'scope' => '',
                ]);

                return $response->json();
            }
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }

        return null;
    }
}

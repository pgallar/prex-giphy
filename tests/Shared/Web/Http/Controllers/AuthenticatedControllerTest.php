<?php

namespace Tests\Shared\Web\Http\Controllers;

use App\Domain\Entities\User;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\PersonalAccessTokenFactory;
use Tests\TestCase;

class AuthenticatedControllerTest extends TestCase
{
    protected function setAuthenticatedUser(): User
    {
        $user = $this->getUserTest();
        $this->actingAs($user);

        return $user;
    }

    protected function getToken(): string
    {
        $response = Http::post(env('APP_URL') . '/oauth/token', [
            'grant_type' => 'password',
            'client_id' => env('PASSPORT_PASSWORD_CLIENT_ID'),
            'client_secret' => env('PASSPORT_PASSWORD_SECRET'),
            'username' => env('TEST_VALID_USER_EMAIL'),
            'password' => env('TEST_VALID_USER_PASSWORD'),
            'scope' => '',
        ]);

        return $response->json()['access_token'];
    }

    protected function getUserTest(): User
    {
        $user = new User([
            'name' => 'PrexGiphy',
        ]);
        $user->setAttribute('id', 1);

        return $user;
    }
}

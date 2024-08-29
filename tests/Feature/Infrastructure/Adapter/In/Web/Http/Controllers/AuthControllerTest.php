<?php

namespace Tests\Feature\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\AuthUser;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use WithFaker;

    /**
     * Verifica que se retorna un error 400 cuando las credenciales no son válidas.
     */
    public function testSigninReturnsBadRequestForInvalidData()
    {
        $response = $this->postJson('/api/v1/auth/signin', [
            'email' => 'not-an-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    /**
     * Verifica que se retorna un error 401 cuando las credenciales no son correctas.
     */
    public function testSigninReturnsUnauthorizedForInvalidCredentials()
    {
        $authUserMock = $this->createMock(AuthUser::class);
        $authUserMock->method('execute')
            ->willReturn(null);

        $this->app->instance(AuthUser::class, $authUserMock);

        $response = $this->postJson('/api/v1/auth/signin', [
            'email' => $this->faker->safeEmail(),
            'password' => 'correct-password',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['error' => 'Unauthorized']);
    }

    /**
     * Verifica que se retorna un token de acceso cuando las credenciales son correctas.
     */
    public function testSigninReturnsTokenForValidCredentials()
    {
        $authUserMock = $this->createMock(AuthUser::class);
        $authUserMock->method('execute')
            ->willReturn('token');

        $this->app->instance(AuthUser::class, $authUserMock);

        $response = $this->postJson('/api/v1/auth/signin', [
            'email' => $this->faker->safeEmail(),
            'password' => 'correct-password',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['token' => 'token']);
    }
}

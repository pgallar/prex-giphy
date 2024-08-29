<?php

namespace Tests\Feature\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\Entities\User;
use App\Domain\Entities\UserFavorite;
use App\Domain\UseCases\AddUserFavorite;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\Shared\Web\Http\Controllers\AuthenticatedControllerTest;
use Tests\TestCase;

class UserControllerTest extends AuthenticatedControllerTest
{
    use WithFaker;

    /**
     * Verifica que un usuario autenticado puede agregar un GIF a sus favoritos exitosamente.
     */
    public function testAddFavoriteSuccessfully()
    {
        $user = $this->getUserTest();

        $addUserFavoriteMock = $this->createMock(AddUserFavorite::class);
        $addUserFavoriteMock->method('execute')
            ->willReturn(new UserFavorite([
                'gif_id' => 'gif123',
                'user_id' => $user->id,
                'alias' => 'My Favorite GIF'
            ]));

        $this->app->instance(AddUserFavorite::class, $addUserFavoriteMock);

        $response = $this->postJson(
            '/api/v1/user/favorite',
            [
                'gif_id' => 'gif123',
                'alias' => 'My Favorite GIF',
            ],
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(200);
        $response->assertJson([
            'favorite' => [
                'gif_id' => 'gif123',
                'user_id' => $user->id,
                'alias' => 'My Favorite GIF'
            ]
        ]);
    }

    /**
     * Verifica que se retorne un error 409 si el GIF ya ha sido agregado a los favoritos.
     */
    public function testAddFavoriteReturnsConflictForDuplicateEntry()
    {
        // Mock del caso de uso AddUserFavorite para simular una entrada duplicada
        $addUserFavoriteMock = $this->createMock(AddUserFavorite::class);
        $addUserFavoriteMock->method('execute')
            ->willReturn('Duplicate entry');

        $this->app->instance(AddUserFavorite::class, $addUserFavoriteMock);

        $response = $this->postJson(
            '/api/v1/user/favorite',
            [
                'gif_id' => 'gif123',
                'alias' => 'My Favorite GIF',
            ],
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(409);
        $response->assertJson(['error' => 'Duplicate entry']);
    }

    /**
     * Verifica que se retorne un error 400 si la validación falla.
     */
    public function testAddFavoriteReturnsBadRequestForInvalidData()
    {
        $response = $this->postJson(
            '/api/v1/user/favorite',
            [
                'gif_id' => '',
                'alias' => '',
            ],
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }
}

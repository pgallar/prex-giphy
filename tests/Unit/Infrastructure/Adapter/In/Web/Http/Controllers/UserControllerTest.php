<?php

namespace Tests\Unit\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\Entities\UserFavorite;
use App\Domain\UseCases\AddUserFavorite;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\UserController;
use App\Infrastructure\Adapter\In\Web\Http\Requests\UserFavoriteRequest;
use Mockery;
use Tests\Shared\Web\Http\Controllers\AuthenticatedControllerTest;

class UserControllerTest extends AuthenticatedControllerTest
{
    public function testAddFavoriteValidatesRequest()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $controller = new UserController($addUserFavorite);

        $request = UserFavoriteRequest::create('/v1/user/favorite', 'POST', [
            'gif_id' => '',
            'alias' => '',
            'user_id' => -1,
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('errors', json_decode($response->getContent(), true));
    }

    public function testAddFavoriteReturnsDuplicateEntryError()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $addUserFavorite->shouldReceive('execute')->andReturn('Duplicate entry');
        $controller = new UserController($addUserFavorite);

        $this->setAuthenticatedUser();
        $request = UserFavoriteRequest::create('/v1/user/favorite', 'POST', [
            'gif_id' => 'some_gif_id',
            'alias' => 'My Favorite Gif',
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(409, $response->getStatusCode());
        $this->assertEquals(['error' => 'Duplicate entry'], json_decode($response->getContent(), true));
    }

    public function testAddFavoriteReturnsOtherError()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $addUserFavorite->shouldReceive('execute')->andReturn('Some other error');
        $controller = new UserController($addUserFavorite);

        $this->setAuthenticatedUser();
        $request = UserFavoriteRequest::create('/v1/user/favorite', 'POST', [
            'gif_id' => 'BBNYBoYa5VwtO',
            'alias' => 'My Favorite Gif',
            'user_id' => 1,
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertEquals(['error' => 'Some other error'], json_decode($response->getContent(), true));
    }

    public function testAddFavoriteReturnsSuccess()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $favorite = new UserFavorite(['gif_id' => 'BBNYBoYa5VwtO', 'alias' => 'My Favorite Gif', 'user_id' => 1]);
        $addUserFavorite->shouldReceive('execute')->andReturn($favorite);
        $controller = new UserController($addUserFavorite);

        $this->setAuthenticatedUser();
        $request = UserFavoriteRequest::create('/v1/user/favorite', 'POST', [
            'gif_id' => 'BBNYBoYa5VwtO',
            'alias' => 'My Favorite Gif',
            'user_id' => 1,
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['favorite' => $favorite->toArray()], json_decode($response->getContent(), true));
    }
}

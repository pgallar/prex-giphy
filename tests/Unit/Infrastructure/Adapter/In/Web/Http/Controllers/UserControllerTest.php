<?php

namespace Tests\Unit\Infrastructure\Adapter\In\Web\Http\Controllers;

use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;
use App\Domain\UseCases\AddUserFavorite;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\UserController;
use App\Domain\Entities\UserFavorite;

class UserControllerTest extends TestCase
{
    public function testAddFavoriteValidatesRequest()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $controller = new UserController($addUserFavorite);

        $request = Request::create('/add-favorite', 'POST', [
            'gif_id' => '',
            'alias' => '',
            'user_id' => -1,
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', json_decode($response->getContent(), true));
    }

    public function testAddFavoriteReturnsDuplicateEntryError()
    {
        $addUserFavorite = Mockery::mock(AddUserFavorite::class);
        $addUserFavorite->shouldReceive('execute')->andReturn('Duplicate entry');

        $controller = new UserController($addUserFavorite);

        $request = Request::create('/add-favorite', 'POST', [
            'gif_id' => 'some_gif_id',
            'alias' => 'My Favorite Gif',
            'user_id' => 1,
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

        $request = Request::create('/add-favorite', 'POST', [
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

        $request = Request::create('/add-favorite', 'POST', [
            'gif_id' => 'BBNYBoYa5VwtO',
            'alias' => 'My Favorite Gif',
            'user_id' => 1,
        ]);

        $response = $controller->addFavorite($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['favorite' => $favorite->toArray()], json_decode($response->getContent(), true));
    }
}

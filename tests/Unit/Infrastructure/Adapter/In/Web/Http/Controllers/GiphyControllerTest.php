<?php

namespace Tests\Unit\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\GiphyFindByID;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\GiphyController;
use App\Domain\UseCases\GiphySearch;
use Illuminate\Http\Request;
use Tests\TestCase;
use Mockery;

class GiphyControllerTest extends TestCase
{
    public function testSearchValidatesRequest()
    {
        $giphySearch = Mockery::mock(GiphySearch::class);
        $giphyFindByID = Mockery::mock(GiphyFindByID::class);
        $controller = new GiphyController($giphySearch, $giphyFindByID);

        $request = Request::create('/search', 'GET', [
            'query' => '',
            'limit' => 0,
            'offset' => -1,
        ]);

        $response = $controller->search($request);

        $this->assertEquals(422, $response->getStatusCode());
        $this->assertArrayHasKey('errors', json_decode($response->getContent(), true));
    }

    public function testSearchReturnsGifs()
    {
        $giphySearch = Mockery::mock(GiphySearch::class);
        $giphyFindByID = Mockery::mock(GiphyFindByID::class);
        $giphySearch->shouldReceive('execute')->andReturn(['gif1', 'gif2']);

        $controller = new GiphyController($giphySearch, $giphyFindByID);

        $request = Request::create('/search', 'GET', [
            'query' => 'cats',
            'limit' => 5,
            'offset' => 0,
        ]);

        $response = $controller->search($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['gifs' => ['gif1', 'gif2']], json_decode($response->getContent(), true));
    }
}

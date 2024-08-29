<?php

namespace Tests\Unit\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\Entities\Gif;
use App\Domain\Entities\Gifs;
use App\Domain\Entities\Pagination;
use App\Infrastructure\Adapter\In\Web\Http\Requests\GiphySearchRequest;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;
use Mockery;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\GiphyController;
use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;


class GiphyControllerTest extends TestCase
{
    public function testSearchValidatesRequest()
    {
        $giphySearch = Mockery::mock(GiphySearch::class);
        $giphyFindByID = Mockery::mock(GiphyFindByID::class);
        $controller = new GiphyController($giphySearch, $giphyFindByID);

        $request = GiphySearchRequest::create('/v1/gif/search', 'GET', [
            'query' => '',
            'limit' => 0,
            'offset' => -1,
        ]);

        $this->expectException(ValidationException::class);
        $controller->search($request);
    }

    public function testSearchReturnsGifs()
    {
        $giphySearch = Mockery::mock(GiphySearch::class);
        $giphyFindByID = Mockery::mock(GiphyFindByID::class);
        $gifs = new Gifs(
            [new Gif(
                'gif123',
                'https://giphy.com/gifs/gif123',
                'Cats'
            )],
            new Pagination(
                100,
                5,
                0
            )
        );
        $giphySearch->shouldReceive('execute')->andReturn($gifs);

        $controller = new GiphyController($giphySearch, $giphyFindByID);

        $request = GiphySearchRequest::create('/v1/gif/search', 'GET', [
            'query' => 'cats',
            'limit' => 5,
            'offset' => 0,
        ]);

        $response = $controller->search($request);
        $responseArray = json_decode($response->getContent(), true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('gifs', $responseArray);
        $this->assertTrue($this->count($responseArray['gifs']) == 1);
    }
}

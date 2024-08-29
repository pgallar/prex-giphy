<?php

namespace Tests\Feature\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\Entities\Gif;
use App\Domain\Entities\Gifs;
use App\Domain\Entities\Pagination;
use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;
use Tests\Shared\Web\Http\Controllers\AuthenticatedControllerTest;

class GiphyControllerTest extends AuthenticatedControllerTest
{
    /**
     * Verifica que se puedan buscar GIFs exitosamente.
     */
    public function testSearchReturnsGifsSuccessfully()
    {
        $gifsResult = new Gifs(
            [new Gif(
                'gif123',
                'https://giphy.com/gifs/gif123',
                'Cats'
            )],
            new Pagination(
                100,
                1,
                0
            )
        );

        $giphySearchMock = $this->createMock(GiphySearch::class);
        $giphySearchMock->method('execute')
            ->willReturn($gifsResult);
        $this->app->instance(GiphySearch::class, $giphySearchMock);

        $response = $this->getJson(
            '/api/v1/gif/search?query=cat&limit=1&offset=0',
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'gifs'
        ]);
    }

    /**
     * Verifica que se retorne un error 400 cuando los parámetros de búsqueda no son válidos.
     */
    public function testSearchReturnsBadRequestForInvalidData()
    {
        $response = $this->getJson(
            '/api/v1/gif/search?query=cats&limit=0&offset=-1',
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(422);
        $response->assertJsonStructure(['errors']);
    }

    /**
     * Verifica que se pueda obtener un GIF por su ID.
     */
    public function testFindByIDReturnsGifSuccessfully()
    {
        $giphyFindByIDMock = $this->createMock(GiphyFindByID::class);
        $giphyFindByIDMock->method('execute')
            ->willReturn(new Gif(
                'gif123',
                'https://giphy.com/gifs/gif123',
                'Cats'
            ));

        $this->app->instance(GiphyFindByID::class, $giphyFindByIDMock);

        $response = $this->getJson(
            '/api/v1/gif/gif123',
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'gifs' => ['id', 'url', 'title']
        ]);
    }

    /**
     * Verifica que se retorne un error 404 cuando el GIF no se encuentra.
     */
    public function testFindByIDReturnsNotFoundForInvalidID()
    {
        $giphyFindByIDMock = $this->createMock(GiphyFindByID::class);
        $giphyFindByIDMock->method('execute')
            ->willReturn(null);

        $this->app->instance(GiphyFindByID::class, $giphyFindByIDMock);

        $response = $this->getJson(
            '/api/v1/gif/invalid_id',
            ["Authorization" => 'Bearer '. $this->getToken()]
        );

        $response->assertStatus(404);
    }
}

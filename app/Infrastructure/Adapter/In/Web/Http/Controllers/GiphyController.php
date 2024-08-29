<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;
use App\Infrastructure\Adapter\In\Web\Http\Requests\GiphySearchRequest;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class GiphyController extends Controller
{
    private GiphySearch $giphySearch;
    private GiphyFindByID $giphyFindByID;

    /**
     * @param GiphySearch $giphySearch
     * @param GiphyFindByID $giphyFindByID
     */
    public function __construct(GiphySearch $giphySearch, GiphyFindByID $giphyFindByID)
    {
        $this->giphySearch = $giphySearch;
        $this->giphyFindByID = $giphyFindByID;
    }

    /**
     * Retorna listado de gifs encontrados por la búsqueda basada en Query, Limit y Offset
     *
     * @param GiphySearchRequest $request
     * @return Application|Response|JsonResponse|\Illuminate\Contracts\Foundation\Application|ResponseFactory
     */
    public function search(GiphySearchRequest $request):
        \Illuminate\Foundation\Application|\Illuminate\Http\Response|
        \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|
        \Illuminate\Contracts\Routing\ResponseFactory
    {
        $request->validate($request->rules());
        $gifs = $this->giphySearch->execute($request->getQuery(), $request->getLimit(), $request->getOffset());

        return response()->json(['gifs' => $gifs]);
    }

    /**
     * Retorna datos gif en base al gifID
     *
     * @param GiphySearchRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function findByID(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $gifs = $this->giphyFindByID->execute($id);

        if (!$gifs) {
            return response()->json(null, 404);
        }

        return response()->json(['gifs' => $gifs]);
    }
}

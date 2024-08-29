<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;
use App\Infrastructure\Adapter\In\Web\Http\Requests\GiphySearchRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GiphyController extends Controller
{
    private GiphySearch $giphySearch;
    private GiphyFindByID $giphyFindByID;

    public function __construct(GiphySearch $giphySearch, GiphyFindByID $giphyFindByID)
    {
        $this->giphySearch = $giphySearch;
        $this->giphyFindByID = $giphyFindByID;
    }

    public function search(GiphySearchRequest $request):
        \Illuminate\Foundation\Application|\Illuminate\Http\Response|
        \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|
        \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $request->validate($request->rules());
        }
        catch(ValidationException $validEx) {
            return response(['errors' => $validEx->getMessage()], 400);
        }

        $gifs = $this->giphySearch->execute($request->getQuery(), $request->getLimit(), $request->getOffset());

        return response()->json(['gifs' => $gifs]);
    }

    public function findByID(Request $request, string $id): \Illuminate\Http\JsonResponse
    {
        $gifs = $this->giphyFindByID->execute($id);

        if (!$gifs) {
            return response()->json(null, 404);
        }

        return response()->json(['gifs' => $gifs]);
    }
}

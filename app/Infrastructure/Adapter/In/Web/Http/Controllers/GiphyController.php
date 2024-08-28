<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GiphyController extends Controller
{
    private GiphySearch $giphySearch;
    private GiphyFindByID $giphyFindByID;

    public function __construct(GiphySearch $giphySearch, GiphyFindByID $giphyFindByID)
    {
        $this->giphySearch = $giphySearch;
        $this->giphyFindByID = $giphyFindByID;
    }

    public function search(Request $request):
        \Illuminate\Foundation\Application|\Illuminate\Http\Response|
        \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|
        \Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
            'limit' => 'int|min:1',
            'offset' => 'int|min:0',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $gifs = $this->giphySearch->execute($request['query'], (int)$request->limit, (int)$request->offset);

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

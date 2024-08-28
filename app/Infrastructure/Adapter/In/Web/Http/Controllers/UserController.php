<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\AddUserFavorite;
use App\Domain\UseCases\AuthUser;
use App\Domain\UseCases\GiphyFindByID;
use App\Domain\UseCases\GiphySearch;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{
    private AddUserFavorite $addUserFavorite;

    public function __construct(AddUserFavorite $addUserFavorite)
    {
        $this->addUserFavorite = $addUserFavorite;
    }

    public function addFavorite(Request $request):
        \Illuminate\Foundation\Application|\Illuminate\Http\Response|
        \Illuminate\Http\JsonResponse|\Illuminate\Contracts\Foundation\Application|
        \Illuminate\Contracts\Routing\ResponseFactory
    {
        $validator = Validator::make($request->all(), [
            'gif_id' => 'required|string|max:50',
            'alias' => 'required|string|max:100',
            'user_id' => 'int|min:0',
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        try {
            $result = $this->addUserFavorite->execute($request->gif_id, (int)$request->user_id, $request->alias);

            if (is_string($result)) {
                if (str_contains($result, 'Duplicate entry')) {
                    return response()->json(['error' => 'Duplicate entry'], 409);
                }

                return response()->json(['error' => $result], 500);
            }
        } catch (BadRequestException $badRequestException) {
            return response()->json(['error' => $badRequestException->getMessage()], 400);
        }

        return response()->json(['favorite' => $result]);
    }
}

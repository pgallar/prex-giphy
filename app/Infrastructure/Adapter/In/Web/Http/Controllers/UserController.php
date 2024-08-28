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

class UserController extends Controller
{
    private AddUserFavorite $addUserFavorite;

    public function __construct(AddUserFavorite $addUserFavorite)
    {
        $this->addUserFavorite = $addUserFavorite;
    }

    public function addFavorite(Request $request)
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

        $result = $this->addUserFavorite->execute($request->gif_id, (int)$request->user_id, $request->alias);

        if (is_string($result)) {
            if (str_contains($result, 'Duplicate entry')) {
                return response()->json(['error' => 'Duplicate entry'], 409);
            }

            return response()->json(['error' => $result], 500);
        }

        return response()->json(['favorite' => $result]);
    }
}

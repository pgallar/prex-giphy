<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\AddUserFavorite;
use App\Infrastructure\Adapter\In\Web\Http\Requests\UserFavoriteRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class UserController extends Controller
{
    private AddUserFavorite $addUserFavorite;

    public function __construct(AddUserFavorite $addUserFavorite)
    {
        $this->addUserFavorite = $addUserFavorite;
    }

    public function addFavorite(UserFavoriteRequest $request):
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

        try {
            $result = $this->addUserFavorite->execute(
                $request->getGifID(),
                Auth::user()->getAuthIdentifier(),
                $request->getAlias()
            );

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

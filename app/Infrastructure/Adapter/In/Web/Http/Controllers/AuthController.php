<?php
namespace App\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\AuthUser;
use App\Infrastructure\Adapter\In\Web\Http\Requests\SigninRequest;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private AuthUser $authUser;

    public function __construct(AuthUser $authUser)
    {
        $this->authUser = $authUser;
    }

    public function signin(SigninRequest $request):
        \Illuminate\Foundation\Application|\Illuminate\Http\Response|
        \Illuminate\Http\JsonResponse|
        \Illuminate\Contracts\Foundation\Application|
        \Illuminate\Contracts\Routing\ResponseFactory
    {
        try {
            $request->validate($request->rules());
        }
        catch(ValidationException $validEx) {
            return response(['errors' => $validEx->getMessage()], 400);
        }

        $token = $this->authUser->execute($request->getEmail(), $request->getPassword());

        if (!$token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token]);
    }
}

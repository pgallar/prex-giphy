<?php

namespace Tests\Unit\Infrastructure\Adapter\In\Web\Http\Controllers;

use App\Domain\UseCases\AuthUser;
use App\Infrastructure\Adapter\In\Web\Http\Controllers\AuthController;
use App\Infrastructure\Adapter\In\Web\Http\Requests\SigninRequest;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function testSigninValidatesRequest()
    {
        $authUser = Mockery::mock(AuthUser::class);
        $controller = new AuthController($authUser);

        $request = SigninRequest::create('/signin', 'POST', [
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response = $controller->signin($request);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertArrayHasKey('errors', json_decode($response->getContent(), true));
    }

    public function testSigninReturnsUnauthorized()
    {
        $authUser = Mockery::mock(AuthUser::class);
        $authUser->shouldReceive('execute')->andReturn(null);

        $controller = new AuthController($authUser);

        $request = SigninRequest::create('/signin', 'POST', [
            'email' => 'giphy@prex.com.ar',
            'password' => 'invalid-password',
        ]);

        $response = $controller->signin($request);

        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals(['error' => 'Unauthorized'], json_decode($response->getContent(), true));
    }

    public function testSigninReturnsTokenOnSuccess()
    {
        $authUser = Mockery::mock(AuthUser::class);
        $authUser->shouldReceive('execute')->andReturn('mocked-token');

        $controller = new AuthController($authUser);

        $request = SigninRequest::create('/signin', 'POST', [
            'email' => 'giphy@prex.com.ar',
            'password' => 'correct-password',
        ]);

        $response = $controller->signin($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['token' => 'mocked-token'], json_decode($response->getContent(), true));
    }
}

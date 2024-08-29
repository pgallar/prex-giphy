<?php

namespace App\Infrastructure\Adapter\In\Web\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Log;
use function PHPUnit\Framework\isNull;


class RequestLogger
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $response = $next($request);

        //here you can check the request to be logged
        $log = [];

        if ($request->user()) {
            $log['USER_ID'] = $request->user()->id;
        }
        $route = $request->route();
        $requestBody = $request->all();

        if (!isNull($route)) {
            if (empty($requestBody)) {
                $requestBody = $route->parameters;
            }

            $log['SERVICE_NAME'] = $route->getName();
        }

        $log['REQUEST_BODY'] = $requestBody;
        $log['STATUS'] = $response->status();
        $log['RESPONSE'] = $response->getContent();
        $log['IP'] = $request->getClientIp();

        Log::info("incoming request", $log);

        return $response;
    }
}

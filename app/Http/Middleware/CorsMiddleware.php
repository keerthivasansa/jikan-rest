<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Laravel\Lumen\Http\ResponseFactory;

class CorsMiddleware
{
    public function __construct(private readonly ResponseFactory $responseFactory)
    {
    }

    public function handle(Request $request, \Closure $next): Response | JsonResponse | RedirectResponse
    {
        if ($request->isMethod('OPTIONS')) {
            $response = $this->responseFactory->make("", 204, $headers);
            $response->setProtocolVersion("1.1");
            $next($request);
            $response->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
            ->header('Access-Control-Max-Age', '86400')
            ->header('Accept-Control-Allow-Headers', 'Accept,Accept-Encoding,DNT,Keep-Alive,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Content-Range,Range')
            ->header('Content-Type', 'text/plain')
            ->header('Content-Length', '0')

        }
        else {
            $response = $next($request);
        }

        return $response;
    }
}

<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAPIKey
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('X-API-Key') ?: $request->get('key'); // Manualy sets key for GET request

        if($key !== env('API_KEY')) {
            return new JsonResponse(['success' => false, 'message' => 'Invalid API key!'], 403);
        }

        return $next($request);
    }
}

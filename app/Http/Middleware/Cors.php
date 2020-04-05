<?php
namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        $headers = [
          'Access-Control-Allow-Origin' => '*',
          'Access-Control-Allow-Methods' => 'HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS',
          'Access-Control-Allow-Headers' => 'Content-Type, x-csrf-token, x-requested-with, Access-Control-Allow-Origin'
        ];

        if ($request->getMethod() === 'OPTIONS') {
            return response(null, 200, $headers);
        }

        $response = $next($request);

        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }
        return $response;
    }
}

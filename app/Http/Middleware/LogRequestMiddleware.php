<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('Request accessed', [
            'method' => $request->method(),
            'uri' => $request->getRequestUri(),
            'payload' => $request->all(),
        ]);

        return $next($request);
    }
}

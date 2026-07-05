<?php

namespace App\Http\Middleware;

use App\Models\ApiPartner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $provided = $request->header('X-API-Key') ?? $request->query('api_key');

        if (!$provided) {
            return response()->json(['error' => 'Missing X-API-Key header.'], 401);
        }

        $partner = ApiPartner::where('api_key', $provided)
            ->where('is_active', true)
            ->first();

        if (!$partner) {
            return response()->json(['error' => 'Invalid or inactive API key.'], 401);
        }

        // Stamp last use without triggering model events
        $partner->updateQuietly(['last_used_at' => now()]);

        // Make partner available to controllers
        $request->attributes->set('api_partner', $partner);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class CheckTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID');

        if (! $tenantId || ! auth()->user()->tenant_id || (int) $tenantId !== auth()->user()->tenant_id) {
            return response()->json(['message' => 'Unauthorized or invalid tenant.'], 403);
        }

        return $next($request);
    }
}

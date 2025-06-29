<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class LogApiRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check for the X-Tenant-ID header and log it.
        $tenantId = $request->header('X-Tenant-ID');
        if ($tenantId) {
            Log::info("Request received for Tenant ID: {$tenantId}");
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        // Only log for authenticated API requests
        if (auth()->check()) {
            $user = auth()->user();
            $tenantId = $request->header('X-Tenant-ID');
            $logMessage = sprintf(
                "API Request Logged | User: %s (ID: %d, Tenant: %s) | Route: %s | Method: %s | Status: %s",
                $user->name,
                $user->id,
                $tenantId ?? 'N/A',
                Route::currentRouteName() ?? $request->path(),
                $request->method(),
                $response->getStatusCode()
            );
            Log::channel('api')->info($logMessage);
        }
    }
}

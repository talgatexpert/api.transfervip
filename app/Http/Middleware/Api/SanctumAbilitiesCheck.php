<?php

namespace App\Http\Middleware\Api;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SanctumAbilitiesCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param mixed ...$abilities
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$abilities)
    {
        foreach ($abilities as $ability) {
            if (!$request->user()->tokenCan($ability)) {
                abort(400, 'Access denied');
            }
        }

        return $next($request);
    }
}

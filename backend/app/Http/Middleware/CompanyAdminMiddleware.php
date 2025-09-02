<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isCompanyAdmin()) {
            return response()->json([
                'error' => 'Acesso negado. Apenas administradores da empresa podem acessar esta funcionalidade.'
            ], 403);
        }

        return $next($request);
    }
}

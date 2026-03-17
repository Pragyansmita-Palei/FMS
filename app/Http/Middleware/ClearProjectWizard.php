<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClearProjectWizard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
   public function handle($request, Closure $next)
{
    // Allowed wizard routes
    $allowedRoutes = [
        'projects.create',
        'projects.store.step1',
        'projects.store.step2',
    ];

    if(!in_array($request->route()->getName(), $allowedRoutes)){
        session()->forget(['project_id','step1','step2']);
    }

    return $next($request);
}

}

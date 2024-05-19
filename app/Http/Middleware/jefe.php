<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
class jefe
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('usuario')->check()) {

            $user = Auth::guard('usuario')->user();

            if ($user->esJefe == 1) {
                return $next($request);
            }
        }

        return redirect()->route('login_form')->with('error' , 'Por favor, inicia sesión primero.');
    }
}

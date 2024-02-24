<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PatientMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!Auth::check()){
            return response()->json(['error' => 'non connecté'], 422);

        }
        if(Auth::user()->role == null){
            return response()->json(['error' => 'role n\'existe pas'], 422);

        }
       
        if(Auth::check() && Auth::user()->role->nom =='patient' ){

            return $next($request);
        }else{
            return response()->json(['error' => 'non authorisé'], 403);

        }
    }
}

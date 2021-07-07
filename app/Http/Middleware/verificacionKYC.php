<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class verificacionKYC
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (Auth::user()->admin != 1) {
            if(Auth::user()->dni != NULL && Auth::user()->verify == 1){
                return $next($request);
            }else{
                return back()->with('msj-danger', 'Necesita verificarse con KYC');
            }
        }else{
            return $next($request);
        }
        
    }
}
  
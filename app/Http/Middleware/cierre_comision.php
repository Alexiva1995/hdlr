<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class cierre_comision
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
        $cierre = Cache::get('cierre_comision');
        if ($cierre != null) {
            if($cierre == true){
                return back()->with('msj-danger', 'Por favor intente mas tarde.');
            }else{
                return $next($request);    
            }
        }else{
            return $next($request);
        }
    }
}

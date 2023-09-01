<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCompanyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->guard('company_api')->user()){
            return response()->json([
                'message'=>'you didnt make login before',
            ]);
        }else if(auth()->guard('company_api')->user()->is_active == 0){
            return response()->json([
                'message'=>'you didnt make login before',
            ]);
        }
        return $next($request);
    }
}

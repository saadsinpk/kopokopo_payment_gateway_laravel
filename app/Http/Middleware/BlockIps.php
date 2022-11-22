<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockIps
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
        try{
            $ipsDeny = [];
            if(in_array(request()->ip(), $ipsDeny))
            {
                return response()->view('vendor.errors.page', ['code'=>403,'message' => "Unauthorized access, IP address was <b>".request()->ip()."</b>"]);
            }
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
        return $next($request);
    }
}

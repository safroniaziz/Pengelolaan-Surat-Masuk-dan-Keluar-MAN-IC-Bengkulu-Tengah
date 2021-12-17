<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsStafTu
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
        if($request->user() && $request->user()->hakAkses == 'staf_tu'){
            return $next($request);
        }
        $notification = array(
            'message' => 'Gagal, anda tidak memiliki akses staf tata usaha!',
            'alert-type' => 'error'
        );
        return redirect()->route('login')->with($notification);
    }
}

<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // return redirect(RouteServiceProvider::HOME);
                if (auth()->user()->hakAkses == "admin") {
                    $notification1 = array(
                        'message' => 'Berhasil, akun login sebagai admin!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('admin.dashboard')->with($notification1);;
                }elseif (auth()->user()->hakAkses == "staf_tu") {
                    $notification2 = array(
                        'message' => 'Berhasil, anda login sebagai Staf Tata Usaha!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('staf_tu.dashboard')->with($notification2);;
                } elseif (auth()->user()->hakAkses == "pimpinan") {
                    $notification2 = array(
                        'message' => 'Berhasil, anda login sebagai Pimpinan!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('pimpinan.dashboard')->with($notification2);;
                } else {
                    Auth::logout();
                    $notification = array(
                        'message' => 'Gagal, akun anda tidak dikenali!',
                        'alert-type' => 'error'
                    );
                    return redirect()->route('login')->with($notification);
                }
            }
        }

        return $next($request);
    }
}

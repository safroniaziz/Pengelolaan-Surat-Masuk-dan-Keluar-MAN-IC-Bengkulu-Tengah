<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $input = $request->all();
        $messages = [
            'required' => ':attribute harus diisi',
        ];
        $attributes = [
            'nip'    =>  'Nip Pegawai',
            'password'    =>  'Password',
        ];
        $this->validate($request,[
            'nip' =>  'required',
            'password' =>  'required',
        ],$messages,$attributes);
        if (auth()->attempt(array('nip'   =>  $input['nip'], 'password' =>  $input['password'],'status'  =>  'aktif'))) {
            if (Auth::check()) {
                if (auth()->user()->hakAkses == "admin") {
                    $notification1 = array(
                        'message' => 'Berhasil, akun login sebagai admin!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('admin.dashboard')->with($notification1);;
                }elseif (auth()->user()->hakAkses == "staf_tu") {
                    $notification2 = array(
                        'message' => 'Berhasil, akun login sebagai Staf Tata Usaha!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('staf_tu.dashboard')->with($notification2);;
                }elseif (auth()->user()->hakAkses == "Pimpinan") {
                    $notification2 = array(
                        'message' => 'Berhasil, akun login sebagai pimpinan!',
                        'alert-type' => 'success'
                    );
                    return redirect()->route('Pimpinan.dashboard')->with($notification2);;
                }else {
                    Auth::logout();
                    $notification = array(
                        'message' => 'Gagal, akun anda tidak dikenali!',
                        'alert-type' => 'error'
                    );
                    return redirect()->route('login')->with($notification);
                }
            } else {
                return redirect()->route('login')->with('error','Password salah atau akun sudah tidak aktif');
            }
        }else{
            $notification = array(
                'message' => 'Gagal, Password salah atau akun sudah tidak aktif!',
                'alert-type' => 'error'
            );
            return redirect()->route('login')->with($notification);
        }
        
    }

    public function username()
    {
        return 'nip';
    }

    public function logout(){
        Auth::guard()->logout();
        return redirect()->route('login');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

    public function index(){
        $users = User::all();
        
        $jabatan = DB::table('tb_jabatan')->select('id','namaJabatan')->get();
        // $user = User::leftJoin('tb_jabatan','tb_jabatan.id','tb_user.jabatanId')->get();

        return view('admin/user.index',compact('users','jabatan'));
    }
    public function add(){
        $user = DB::table('tb_user')->select(  
        'jabatanId',
        'nip',
        'namaUser',
        'email',
        'password',
        'telephone',
        'hakAkses',
        'status')->get();
        $jabatan = DB::table('tb_jabatan')->select('id','namaJabatan')->get();
       
        return view('admin/user.add',compact('user','jabatan'));
    }
    public function post(Request $request){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
            'mimes' => 'The :attribute harus berupa file: :values.',
            'max' => [
                'file' => ':attribute tidak boleh lebih dari :max kilobytes.',
            ],
        ];
        $attributes = [
            'nm_user'   =>  'Nama User',
            'keterangan'   =>  'keterangan',
      
        ];
        $this->validate($request, [
           
        ],$messages,$attributes);
       
        User::create([
       
            'jabatanId' =>  $request->jabatanId,
            'nip'  =>  $request->nip,
            'namaUser' =>  $request->namaUser,
            'email' =>  $request->email,
            'password' => bcrypt($request->password),
            'telephone' =>  $request->telephone,
            'hakAkses' =>  $request->hakAkses,
            'status' =>  $request->status,
        ]);

        $notification = array(
            'message' => 'Berhasil, data user berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.user')->with($notification);
    }

    public function edit($id){
        $data = User::where('id',$id)->first();
        $jabatan = DB::table('tb_jabatan')->select('id','namaJabatan')->get();
     
        return view('admin/user.edit',compact('data','jabatan'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
            'namaUser'   =>  ' User',
            'keterangan'   =>  ' Keterangan',
        ];
        $this->validate($request, [
        ],$messages,$attributes);
        
        User::where('id',$id)->update([
            'jabatanId' =>  $request->jabatan,
            'nip'  =>  $request->nip,
            'namaUser' =>  $request->namaUser,
            'email' =>  $request->email,
            // 'password' =>  bcrypt($request->password),
            'telephone' =>  $request->telephone,
            'hakAkses' =>  $request->hakAkses,
           
            ]);

            $notification = array(
                'message' => 'Berhasil, data user berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.user')->with($notification);
        }
    public function delete($id){
        User::where('id',$id)->delete();
        $notification = array(
            'message' => 'Berhasil, data user berhasil dihapus!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.user')->with($notification);
    }
    public function nonAktifkanStatus($id){
        User::where('id',$id)->update([
            'status'    =>  'nonaktif'
        ]);
        return redirect()->route('admin.user')->with(['success' =>  'User Berhasil Di Nonaktifkan !!']);
    }

    public function aktifkanStatus($id){
        User::where('id',$id)->update([
            'status'    =>  'aktif'
        ]);
        return redirect()->route('admin.user')->with(['success' =>  'User Berhasil Di Aktifkan !!']);
    }

}

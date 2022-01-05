<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JabatanController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

    public function index(){
        $jabatans = Jabatan::all();
        return view('admin/jabatan.index',compact('jabatans'));
    }
    public function add(){
        $jabatan = DB::table('tb_jabatan')->select('nm_jabatan','keterangan')->get();
       
        return view('admin/jabatan.add',compact('jabatan'));
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
            'nm_jabatan'   =>  'Nama Jabatan',
            'keterangan'   =>  'keterangan',
        ];
        $this->validate($request, [
            'nm_jabatan'    =>  'required',
        ],$messages,$attributes);
       
        Jabatan::create([
            'namaJabatan'    =>  $request->nm_jabatan,
            'keterangan'    =>  $request->keterangan,
        ]);

        $notification = array(
            'message' => 'Berhasil, data jabatan berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.jabatan')->with($notification);
    }

    public function edit($id){
        $data = Jabatan::where('id',$id)->first();
     
        return view('admin/jabatan.edit',compact('data'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
            'namaJabatan'   =>  ' Jabatan',
            'keterangan'   =>  ' Keterangan',
        ];
        $this->validate($request, [
        ],$messages,$attributes);
        
        Jabatan::where('id',$id)->update([
                'namaJabatan'    =>  $request->nm_jabatan,
                'keterangan'    =>  $request->keterangan,
            ]);

            $notification = array(
                'message' => 'Berhasil, data jabatan berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.jabatan')->with($notification);
        }
    public function delete($id){
        Jabatan::where('id',$id)->delete();
        $notification = array(
            'message' => 'Berhasil, data jabatan berhasil dihapus!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.jabatan')->with($notification);
    }

    
}

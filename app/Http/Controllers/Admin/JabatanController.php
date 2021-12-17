<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Jabatan;
use Illuminate\Support\Facades\DB;

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
            'nm_jabatan'    =>  $request->nm_jabatan,
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

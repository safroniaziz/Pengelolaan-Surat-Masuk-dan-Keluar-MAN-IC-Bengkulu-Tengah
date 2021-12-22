<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class JenisSuratController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

    public function index(){
        $jenissurats = JenisSurat::all();
        return view('admin/jenis_surat.index',compact('jenissurats'));
    }
    public function add(){
        $jenissurat = DB::table('tb_jenis_surat')->select('jenisSurat')->get();
       
        return view('admin/jenis_surat.add',compact('jenissurat'));
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
            'jenisSurat'   =>  'Nama JenisSurat',
            'keterangan'   =>  'keterangan',
      
        ];
        $this->validate($request, [
            'jenisSurat'    =>  'required',
        ],$messages,$attributes);
       
        JenisSurat::create([
            'jenisSurat'    =>  $request->jenisSurat,
           
        ]);

        $notification = array(
            'message' => 'Berhasil, data jenissurat berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.jenis_surat')->with($notification);
    }

    public function edit($id){
        $data = JenisSurat::where('id',$id)->first();
     
        return view('admin/jenis_surat.edit',compact('data'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
            'namaJenisSurat'   =>  ' JenisSurat',
            'keterangan'   =>  ' Keterangan',
        ];
        $this->validate($request, [
        ],$messages,$attributes);
        
        JenisSurat::where('id',$id)->update([
                'jenisSurat'    =>  $request->jenisSurat,
           
            ]);

            $notification = array(
                'message' => 'Berhasil, data jenis_surat berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.jenis_surat')->with($notification);
        }
    public function delete($id){
        JenisSurat::where('id',$id)->delete();
        $notification = array(
            'message' => 'Berhasil, data jenis_surat berhasil dihapus!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.jenis_surat')->with($notification);
    }
}

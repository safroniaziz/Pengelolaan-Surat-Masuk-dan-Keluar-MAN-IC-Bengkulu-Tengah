<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratKeluar;
use App\Models\JenisSurat;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
class SuratKeluarController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }
    public function index(){
        $suratkeluars = SuratKeluar::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_keluar.jenisSuratId')
        ->select('tb_surat_keluar.id','jenisSurat','penerima','nomorSurat','perihal',
                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat',
                'tb_surat_keluar.created_at')
        ->orderBy('created_at','desc')
        ->get();
       
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
        // $suratkeluars = SuratKeluar::leftJoin('tb_jenis_surat','tb_jenis_surat.id','tb_surat_keluar.jenisSuratId')->get();
        return view('admin/surat_keluar.index',compact('suratkeluars','jenissurat'));
    }
    public function add(){
        $suratkeluar = DB::table('tb_surat_keluar')->select('jenisSuratId','penerima','nomorSurat','perihal','tujuan','lampiran','catatan','sifatSurat')->get();
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
        return view('admin/surat_keluar.add',compact('suratkeluar','jenissurat'));
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
            
        ],$messages,$attributes);
        $model = $request->all();
        $model['lampiran'] = null;
        $slug_user = Str::slug(Auth::user()->namaUser);

        if ($request->hasFile('lampiran')) {
            $model['lampiran'] = $slug_user.'-'.Auth::user()->namaUser.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_keluar/'.$slug_user), $model['lampiran']);
        }
        SuratKeluar::create([
            'jenisSuratId' =>  $request->jenissurat,
            'nomorSurat'  =>  $request->nomorSurat,
            'penerima'  =>  $request->penerima,
            'perihal'   =>  $request->perihal,
            'tujuan' =>  $request->tujuan,
            'tanggalSurat' =>  $request->tanggalSurat,

            'catatan' =>  $request->catatan,
            'sifatSurat' =>  $request->sifatSurat,
            'lampiran'    =>  $model['lampiran'],
            
         
    
        ]);

        $notification = array(
            'message' => 'Berhasil, data jabatan berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.surat_keluar')->with($notification);
    }

    public function delete($id){
        SuratKeluar::where('id',$id)->delete();
        $notification = array(
            'message' => 'Berhasil, data surat keluar berhasil dihapus!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.surat_keluar')->with($notification);
    }
    public function edit($id){
        $data = SuratKeluar::where('id',$id)->first();
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
     
        return view('admin/surat_keluar.edit',compact('data','jenissurat'));
    }
    public function update(Request $request, $id){
        $messages = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus angka',
        ];
        $attributes = [
           
        ];
        $this->validate($request, [
        ],$messages,$attributes);


         $model = $request->all();
        $model['lampiran'] = null;
        $slug_user = Str::slug(Auth::user()->namaUser);

        if ($request->hasFile('lampiran')) {
            $model['lampiran'] = $slug_user.'-'.Auth::user()->namaUser.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_keluar/'.$slug_user), $model['lampiran']);
        }
        // return $data;

        SuratKeluar::where('id',$id)->update([
            'jenisSuratId' =>  $request->jenissurat,
            'nomorSurat'  =>  $request->nomorSurat,
            'penerima'  =>  $request->penerima,
            'perihal'   =>  $request->perihal,
            'tujuan' =>  $request->tujuan,
            'tanggalSurat' =>  $request->tanggalSurat,

            'catatan' =>  $request->catatan,
            'sifatSurat' =>  $request->sifatSurat,
            'lampiran'    =>  $model['lampiran'],

            ]);

            $notification = array(
                'message' => 'Berhasil, data surat_keluar berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.surat_keluar')->with($notification);
        }
}

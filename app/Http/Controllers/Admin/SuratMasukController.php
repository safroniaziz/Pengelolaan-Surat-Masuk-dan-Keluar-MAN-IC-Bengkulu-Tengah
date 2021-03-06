<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\JenisSurat;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use App\Models\DisposisiSurat;

class SuratMasukController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

    public function index(){
        $suratmasuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
        ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                'statusBaca','tb_surat_masuk.created_at')
        ->orderBy('created_at','desc')
        ->get();
      
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
        // $ruangans = PenggunaRuang::join('ruangans','ruangans.id','pengguna_ruangs.ruang_id')->get();

        // $suratmasuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')->get();
        return view('admin/surat_masuk.index',compact('suratmasuks','jenissurat'));
    }
    public function add(){
        $suratmasuk = DB::table('tb_surat_masuk')->select('jenisSuratId','pengirimSurat','nomorSurat','perihal','tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan','statusBaca')->get();
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
        return view('admin/surat_masuk.add',compact('suratmasuk','jenissurat'));
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
            'nm_surat_masuk'   =>  'Nama SuratMasuk',
            'keterangan'   =>  'keterangan',
      
        ];
        $this->validate($request, [
            'jenisSuratId'  =>  'required',
            'pengirimSurat'  =>  'required',
            'nomorSurat'  =>  'required',
            'perihal'  =>  'required',
            'tujuan'  =>  'required',
            'lampiran'  =>  'required|mimes:doc,pdf,docx,jpg|max:1000',
            'catatan'  =>  'required',
            'sifatSurat'  =>  'required',
            'tanggalSurat'  =>  'required',
        ],$messages,$attributes);

        $model = $request->all();
        $model['lampiran'] = null;
        $slug_user = Str::slug(Auth::user()->namaUser);

        if ($request->hasFile('lampiran')) {
            $model['lampiran'] = $slug_user.'-'.Auth::user()->namaUser.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_masuk/'.$slug_user), $model['lampiran']);
        }
        SuratMasuk::create([
            'jenisSuratId'=>  $request->jenissurat,
            'nomorSurat'    =>  $request->nomorSurat,
            'pengirimSurat'=>  $request->pengirimSurat,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'lampiran'    =>  $model['lampiran'],
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
            // 'statusTeruskan'=>  $request->statusTeruskan,
            // 'statusBaca'=>  $request->statusBaca,
        ]);

        $notification = array(
            'message' => 'Berhasil, data surat_masuk berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('admin.surat_masuk')->with($notification);
    }

    
    public function edit($id){
        $data = SuratMasuk::where('id',$id)->first();
        $jenissurat = DB::table('tb_jenis_surat')->select('id','jenisSurat')->get();
     
        return view('admin/surat_masuk.edit',compact('data','jenissurat'));
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
            ->move(public_path('/upload/surat_masuk/'.$slug_user), $model['lampiran']);
        }
        // return $data;

        SuratMasuk::where('id',$id)->update([
            'jenisSuratId'=>  $request->jenissurat,
            'nomorSurat'    =>  $request->nomorSurat,
            'pengirimSurat'=>  $request->pengirimSurat,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
            // 'statusTeruskan'=>  $request->statusTeruskan,
            // 'statusBaca'=>  $request->statusBaca,
            'lampiran'    =>  $model['lampiran'],

            ]);

            $notification = array(
                'message' => 'Berhasil, data surat_masuk berhasil ditambahkan!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.surat_masuk')->with($notification);
        }
        public function delete($id){
            SuratMasuk::where('id',$id)->delete();
            $notification = array(
                'message' => 'Berhasil, data surat masuk berhasil dihapus!',
                'alert-type' => 'success'
            );
            return redirect()->route('admin.surat_masuk')->with($notification);
        }
        public function bacaSurat($id, Request $request){
            $user = $request->pengirimSurat;
            $file = $request->lampiran;
            $path = 'upload/surat_masuk/'.\Illuminate\Support\Str::slug($user).'/'.$file;
            return response()->file($path);
        }
}

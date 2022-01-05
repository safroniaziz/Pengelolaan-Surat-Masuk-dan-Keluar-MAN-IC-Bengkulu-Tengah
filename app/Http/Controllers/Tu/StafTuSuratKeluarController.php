<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StafTuSuratKeluarController extends Controller
{
    public function index(){
        $surat_keluars = SuratKeluar::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_keluar.jenisSuratId')
                        ->select('tb_surat_keluar.id','jenisSurat','penerima','nomorSurat','perihal',
                                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat',
                                'tb_surat_keluar.created_at')
                        ->orderBy('created_at','desc')
                        ->get();
        // return $surat_keluars;
        return view('staf_tu/surat_keluar.index',compact('surat_keluars'));
    }

    public function add(){
        $jenissurat = JenisSurat::select('id','jenisSurat')->get();
        return view('staf_tu/surat_keluar.add',compact('jenissurat'));
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
            'jenisSuratId'   =>  'Jenis Surat',
            'penerima'   =>  'Pengirim Surat',
            'nomorSurat'   =>  'Nomor Surat',
            'perihal'   =>  'Perihal',
            'tujuan'   =>  'Tujuan',
            'lampiran'   =>  'Lampiran',
            'catatan'   =>  'catatan',
            'sifatSurat'   =>  'Sifat Surat',
            'tanggalSurat'   =>  'Tanggal Surat',
        ];
        $this->validate($request, [
            'jenisSuratId'  =>  'required',
            'penerima'  =>  'required',
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
            $model['lampiran'] = $slug_user.'-'.$slug_user.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_keluar/'.$slug_user), $model['lampiran']);
        }
        SuratKeluar::create([
            'jenisSuratId'=>  $request->jenisSuratId,
            'nomorSurat'    =>  $request->nomorSurat,
            'penerima'=>  $request->penerima,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'lampiran'    =>  $model['lampiran'],
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
        ]);

        $notification = array(
            'message' => 'Berhasil, data surat keluar berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('staf_tu.surat_keluar')->with($notification);
    }

    public function detail($detailId){
        $detail = SuratKeluar::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_keluar.jenisSuratId')
                            ->select('tb_surat_keluar.id','jenisSurat','penerima','nomorSurat','perihal','tujuan','lampiran',
                                        'catatan','sifatSurat','tanggalSurat')
                            ->where('tb_surat_keluar.id',$detailId)
                            ->first();
        return $detail;
    }
}

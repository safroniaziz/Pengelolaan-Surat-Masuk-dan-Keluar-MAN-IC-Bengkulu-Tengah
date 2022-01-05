<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\JenisSurat;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class StafTuSuratMasukController extends Controller
{
    public function index(){
        $surat_masuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                        ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                                'statusBaca','tb_surat_masuk.created_at')
                        ->orderBy('created_at','desc')
                        ->get();
        // return $surat_masuks;
        return view('staf_tu/surat_masuk.index',compact('surat_masuks'));
    }

    public function add(){
        $jenissurat = JenisSurat::select('id','jenisSurat')->get();
        return view('staf_tu/surat_masuk.add',compact('jenissurat'));
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
            'pengirimSurat'   =>  'Pengirim Surat',
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
            $model['lampiran'] = $slug_user.'-'.$slug_user.'-'.date('now').'.'.$request->lampiran->getClientOriginalExtension();
            $request->lampiran
            ->move(public_path('/upload/surat_masuk/'.$slug_user), $model['lampiran']);
        }
        SuratMasuk::create([
            'jenisSuratId'=>  $request->jenisSuratId,
            'nomorSurat'    =>  $request->nomorSurat,
            'pengirimSurat'=>  $request->pengirimSurat,
            'perihal'    =>  $request->perihal,
            'tujuan'=>  $request->tujuan,
            'lampiran'    =>  $model['lampiran'],
            'catatan'=>  $request->catatan,
            'sifatSurat'=>  $request->sifatSurat,
            'tanggalSurat'=>  $request->tanggalSurat,
        ]);

        $notification = array(
            'message' => 'Berhasil, data surat masuk berhasil ditambahkan!',
            'alert-type' => 'success'
        );
        return redirect()->route('staf_tu.surat_masuk')->with($notification);
    }

    public function detail($id){
        $detail = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                            ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal','tujuan','lampiran',
                                        'catatan','sifatSurat','tanggalSurat','statusTeruskan','statusBaca')
                            ->where('tb_surat_masuk.id',$id)
                            ->first();
        return view('staf_tu/surat_masuk.detail',compact('detail'));
    }

    public function teruskan(Request $request){
        SuratMasuk::where('id',$request->teruskanId)->update([
            'statusTeruskan'    =>  'sudah',
        ]);
        $notification = array(
            'message' => 'Berhasil, data surat masuk berhasil diteruskan!',
            'alert-type' => 'success'
        );
        return redirect()->route('staf_tu.surat_masuk')->with($notification);
    }
}

<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;

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
}

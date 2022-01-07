<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PimpinanSuratMasukController extends Controller
{
    public function index(){
        if (Auth::user()->jabatan->namaJabatan == "Kepala Sekolah") {
            $surat_masuks = SuratMasuk::join('tb_jenis_surat','tb_jenis_surat.id','tb_surat_masuk.jenisSuratId')
                        ->select('tb_surat_masuk.id','jenisSurat','pengirimSurat','nomorSurat','perihal',
                                'tujuan','lampiran','catatan','sifatSurat','tanggalSurat','statusTeruskan',
                                'statusBaca','tb_surat_masuk.created_at')
                        ->where('statusTeruskan','sudah')
                        ->orderBy('created_at','desc')
                        ->get();
        }
                        // return $surat_masuks;
        return view('pimpinan/surat_masuk.index',compact('surat_masuks'));
    }
}

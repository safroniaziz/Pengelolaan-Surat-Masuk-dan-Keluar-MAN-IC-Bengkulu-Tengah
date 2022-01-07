<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PimpinanDashboardController extends Controller
{
    public function dashboard(){
        $surat_masuk = count(SuratMasuk::all());
        $surat_keluar = count(SuratKeluar::all());
        $dt = Carbon::now();
        $today = $dt->toDateString();
        $masuk_hari_ini = count(SuratMasuk::whereDate('created_at',$today)->get());
        $keluar_hari_ini = count(SuratKeluar::whereDate('created_at',$today)->get());
        $persentase = array([
                'title' =>  'Surat Masuk',
                'jumlah'    =>  $surat_masuk,
            ],
            [
                'title' =>  'Surat Keluar',
                'jumlah'    =>  $surat_keluar,
            ],
        );
        return view('pimpinan.dashboard',compact('surat_masuk','surat_keluar','masuk_hari_ini','keluar_hari_ini','persentase'));
    }
}

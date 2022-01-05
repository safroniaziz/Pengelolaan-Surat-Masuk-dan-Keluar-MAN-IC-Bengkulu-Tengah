<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','IsAdmin']);
    }

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
        return view('admin.dashboard',compact('surat_masuk','surat_keluar','masuk_hari_ini','keluar_hari_ini','persentase'));
    }
    }


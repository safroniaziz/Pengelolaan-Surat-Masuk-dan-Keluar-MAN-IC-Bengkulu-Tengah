<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratKeluar extends Model
{
    use HasFactory;
    protected $table = 'tb_surat_keluar';
    protected $fillable = [
        'jenisSuratId',
        'nomorSurat',
        'penerima',
        'perihal',
        'tujuan',
        'lampiran',
        'catatan',
        'sifatSurat',
        'tanggalSurat',
        'tanggalSuratKeluar',
        'ditujukanKe',


    ];
}

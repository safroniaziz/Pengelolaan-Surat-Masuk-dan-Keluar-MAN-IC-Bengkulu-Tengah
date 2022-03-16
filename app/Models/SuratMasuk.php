<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    use HasFactory;
    protected $table = 'tb_surat_masuk';
    protected $fillable = [
        'jenisSuratId',
        'nomorSurat',
        'pengirimSurat',
        'perihal',
        'tujuan',
        'lampiran',
        'catatan',
        'sifatSurat',
        'tanggalSurat',
        'tanggalSuratMasuk',
        'statusTeruskan',
        'statusBaca',
        'penginputId',
        'teruskanId'
    ];
}

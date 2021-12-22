<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisposisiSurat extends Model
{
    use HasFactory;
    protected $table = 'tb_disposisi_surat';
    protected $fillable = [
        'suratMasukId',
        'pengirimId',
        'penerimaId',
        'statusDisposisi',
        'statusBacaDisposisi',
       
    ];

}

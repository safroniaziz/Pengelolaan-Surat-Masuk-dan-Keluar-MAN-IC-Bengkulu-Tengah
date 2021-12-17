<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'tb_jabatan';
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'nm_jabatan',
        'keterangan',
       
       
    ];
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSuratMasukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_surat_id');
            $table->string('pengirim_surat');
            $table->string('no_surat')->length(30)->unique();
            $table->string('perihal')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('sifat_surat',['penting','segera','rahasia','biasa']);
            $table->date('tanggal_surat');
            $table->enum('status_teruskan',['sudah','belum']);
            $table->enum('status_baca',['sudah','belum']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_surat_masuk');
    }
}

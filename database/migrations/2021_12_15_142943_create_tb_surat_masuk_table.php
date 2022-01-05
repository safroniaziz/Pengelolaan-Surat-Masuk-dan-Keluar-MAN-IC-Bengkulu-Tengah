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
            $table->unsignedBigInteger('jenisSuratId');
            $table->string('pengirimSurat');
            $table->string('nomorSurat')->length(30)->unique();
            $table->string('perihal')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('sifatSurat',['penting','segera','rahasia','biasa']);
            $table->date('tanggalSurat');
            $table->enum('statusTeruskan',['sudah','belum'])->default('belum');
            $table->enum('statusBaca',['sudah','belum'])->default('belum');
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

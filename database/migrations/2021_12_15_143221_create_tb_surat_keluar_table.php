<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSuratKeluarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_surat_id');
            $table->string('penerima')->length(30);
            $table->string('no_surat')->length(30);
            $table->string('perihal')->nullable();
            $table->string('tujuan')->nullable();
            $table->string('lampiran')->nullable();
            $table->string('catatan')->nullable();
            $table->enum('sifat_surat',['penting','segera','biasa','rahasia']);
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
        Schema::dropIfExists('tb_surat_keluar');
    }
}

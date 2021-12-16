<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDisposisiSuratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_disposisi_surat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('surat_masuk_id');
            $table->unsignedBigInteger('pengirim_id')->nullable();
            $table->unsignedBigInteger('penerima_id')->nullable();
            $table->enum('status_disposisi',['sudah','belum']);
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
        Schema::dropIfExists('tb_disposisi_surat');
    }
}

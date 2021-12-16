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
            $table->unsignedBigInteger('suratMasukId');
            $table->unsignedBigInteger('pengirimId')->nullable();
            $table->unsignedBigInteger('penerimaId')->nullable();
            $table->enum('statusDisposisi',['sudah','belum']);
            $table->enum('statusBacaDisposisi',['sudah','belum']);
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

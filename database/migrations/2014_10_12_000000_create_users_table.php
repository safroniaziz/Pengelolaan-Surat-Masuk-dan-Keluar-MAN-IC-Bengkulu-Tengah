<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jabatanId')->nullable();
            $table->string('nip');
            $table->string('namaUser');
            $table->string('email');
            $table->string('password');
            $table->string('telephone')->nullable();;
            $table->enum('hakAkses',['pimpinan','staf_tu','admin']);
            $table->enum('status',['aktif','nonaktif'])->defaul('aktif');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

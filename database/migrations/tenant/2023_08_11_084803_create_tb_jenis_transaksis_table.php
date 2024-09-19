<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbJenisTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_jenis_transaksis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tb_induk_jenis_transaksi_id');
            $table->foreign('tb_induk_jenis_transaksi_id')->references('id')->on('tb_induk_jenis_transaksis')->onDelete('cascade');
            $table->string('nama');
            $table->string('slug');
            $table->text('deskripsi')->nullable();
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
        Schema::dropIfExists('tb_jenis_transaksis');
    }
}

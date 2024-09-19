<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbKonfigurasiTransaksisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_konfigurasi_transaksis', function (Blueprint $table) {
            $table->id();
            $table->integer('tb_jenis_transaksi_id')->nullable();
            $table->enum('cara_bayar',['tunai','transfer','tempo','kartu_kredit'])->nullable();
            $table->string('rekening_debit_kode');
            $table->foreign('rekening_debit_kode')->references('kode')->on('tb_rekenings')->onDelete('cascade');
            $table->string('rekening_kredit_kode');
            $table->foreign('rekening_kredit_kode')->references('kode')->on('tb_rekenings')->onDelete('cascade');
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
        Schema::dropIfExists('tb_konfigurasi_transaksis');
    }
}

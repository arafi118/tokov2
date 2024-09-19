<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbJurnalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_jurnal_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tb_jurnal_id');
            $table->foreign('tb_jurnal_id')->references('id')->on('tb_jurnals')->onDelete('cascade');
            $table->unsignedBigInteger('tb_jenis_transaksi_id');
            $table->foreign('tb_jenis_transaksi_id')->references('id')->on('tb_jenis_transaksis')->onDelete('cascade');
            $table->string('debit_kode',50);
            $table->foreign('debit_kode')->references('kode')->on('tb_rekenings')->onDelete('cascade');
            $table->string('kredit_kode',50);
            $table->foreign('kredit_kode')->references('kode')->on('tb_rekenings')->onDelete('cascade');
            $table->decimal('debit_nominal',18,2)->default(0);
            $table->decimal('kredit_nominal',18,2)->default(0);
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
        Schema::dropIfExists('tb_jurnal_details');
    }
}

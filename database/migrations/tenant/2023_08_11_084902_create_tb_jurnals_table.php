<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_jurnals', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
            $table->unsignedBigInteger('tb_induk_jenis_transaksi_id');
            $table->foreign('tb_induk_jenis_transaksi_id')->references('id')->on('tb_induk_jenis_transaksis')->onDelete('cascade');
            $table->date('tgl_transaksi');
            $table->string('nomor_transaksi');
            $table->text('memo')->nullable();
            $table->string('tabel_transaksi');
            $table->integer('referensi_id')->nullable();
            $table->integer('insertedby')->nullable();
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
        Schema::dropIfExists('tb_jurnals');
    }
}

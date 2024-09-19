<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventarisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tb_jurnal_id');
            $table->foreign('tb_jurnal_id')->references('id')->on('tb_jurnals')->onDelete('cascade');
            $table->integer('unit')->default(0);
            $table->integer('harga_satuan')->default(0);
            $table->date('tgl_beli');
            $table->string('jenis');
            $table->string('relasi');
            $table->string('nama_barang');
            $table->integer('umur_ekonomis');
            $table->string('status');
            $table->date('tgl_validasi');
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
        Schema::dropIfExists('inventaris');
    }
}

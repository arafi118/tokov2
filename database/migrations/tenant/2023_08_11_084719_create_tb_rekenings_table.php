<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRekeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rekenings', function (Blueprint $table) {
            $table->id();
            $table->string('kode',50)->index();
            $table->string('nama');
            $table->string('slug');
            $table->integer('parent_id')->nullable();
            $table->integer('depth');
            $table->decimal('saldo',18,2)->default(0);
            $table->enum('jenis_mutasi',['debit','kredit'])->nullable();
            $table->string('no_rek_bank')->nullable();//khusus rekening bank
            $table->string('atas_nama_rek')->nullable();//khusus rekening bank
            $table->string('kategori')->nullable();
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
        Schema::dropIfExists('tb_rekenings');
    }
}

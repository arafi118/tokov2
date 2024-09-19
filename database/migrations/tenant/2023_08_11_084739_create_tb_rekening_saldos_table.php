<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRekeningSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_rekening_saldos', function (Blueprint $table) {  
            $table->id();
            $table->string('tb_rekening_kode');
            $table->foreign('tb_rekening_kode')->references('kode')->on('tb_rekenings')->onDelete('cascade');
            $table->string('tahun');

            for ($i=1; $i < 13; $i++) { 
                $j = $i < 10 ? '0'.$i : $i;
                $table->decimal('debit_'.$j,18,2)->default(0);
                $table->decimal('kredit_'.$j,18,2)->default(0);
            }

            $table->integer('warehouse_id')->nullable();
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
        Schema::dropIfExists('tb_rekening_saldos');
    }
}

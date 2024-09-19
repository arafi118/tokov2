<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('site_title');
            $table->string('site_logo')->nullable();
            $table->string('subdomain')->nullable();
            $table->date('tgl_awal_app')->nullable();
            $table->string('tahun_saldo_awal_tahun')->nullable();
            $table->string('bulan_saldo_awal_tahun')->nullable();
            $table->string('tahun_saldo_bulan_lalu')->nullable();
            $table->string('bulan_saldo_bulan_lalu')->nullable();
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
        Schema::dropIfExists('general_settings');
    }
}

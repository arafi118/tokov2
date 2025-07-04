<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reference_no');
            $table->integer('warehouse_id');
            $table->integer('supplier_id')->nullable();
            $table->integer('item');
            $table->integer('total_qty');
            $table->double('total_discount');
            $table->double('total_cashback');
            $table->double('total_tax');
            $table->double('total_cost');
            $table->double('order_tax_rate')->nullable();
            $table->double('order_discount')->nullable();
            $table->double('order_tax')->nullable();
            $table->string('order_discount_type')->nullable();
            $table->double('order_discount_value')->nullable();
            $table->double('order_discount')->nullable();
            $table->string('order_cashback_type')->nullable();
            $table->double('order_cashback_value')->nullable();
            $table->double('order_cashback')->nullable();
            $table->double('shipping_cost')->nullable();
            $table->double('grand_total');
            $table->double('paid_amount');
            $table->integer('status');
            $table->integer('payment_status');
            $table->string('document')->nullable();
            $table->text('note')->nullable();
            $table->enum('is_po', ['Ya', 'Tidak'])->nullable();
            $table->enum('is_tempo', ['Ya', 'Tidak'])->nullable();
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
        Schema::dropIfExists('purchases');
    }
}

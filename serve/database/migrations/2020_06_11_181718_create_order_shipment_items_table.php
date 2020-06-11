<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderShipmentItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_shipment_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('shipment_id');
            $table->foreign('shipment_id')->references('id')->on('order_shipments')->onDelete('cascade');
            $table->unsignedBigInteger('variant_id');
            $table->string('variant_code')->nullable();
            $table->string('product_unit',45)->nullable();
            $table->string('product_title')->nullable();
            $table->string('variant_title')->nullable();
            $table->decimal('price');
            $table->integer('quantity');
            $table->decimal('weight')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_shipment_items');
    }
}

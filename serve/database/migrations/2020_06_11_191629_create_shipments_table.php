<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('shipment_title');
            $table->boolean('need_cost')->default(false);
            $table->string('cost_type')->nullable();
            $table->decimal('price_1')->nullable();
            $table->decimal('price_2')->nullable();
            $table->decimal('value_1')->nullable();
            $table->decimal('value_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipments');
    }
}

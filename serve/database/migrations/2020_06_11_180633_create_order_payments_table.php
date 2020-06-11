<?php

use App\Models\OrderPayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->string('order_no');
            $table->string('no');
            $table->string('pay_no')->nullable();
            $table->string('payment_method');
            $table->decimal('pay_amount');
            $table->string('status',45)->default(OrderPayment::PAYMENT_STATUS_PENDING);
            $table->timestamp('pay_at')->nullable();
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
        Schema::dropIfExists('order_payments');
    }
}

<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->string('no');
            $table->decimal('items_amount')->default(0);
            $table->decimal('shipments_amount')->default(0);
            $table->decimal('discounts_amount')->default(0);
            $table->decimal('amount')->default(0);
            $table->string('status',20)->default(Order::ORDER_STATUS_PENDING);
            $table->string('refund_status',20)->nullable();
            $table->timestamp('send_at')->nullable();
            $table->timestamp('pay_at')->nullable();
            $table->string('closed_reason')->nullable();
            $table->string('remark')->nullable();
            $table->timestamp('success_at')->nullable();
            $table->timestamp('closed_at')->nullable();
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
        Schema::dropIfExists('orders');
    }
}

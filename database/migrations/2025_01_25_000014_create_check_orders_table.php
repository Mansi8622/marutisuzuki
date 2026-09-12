<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('check_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_number')->nullable();
            $table->decimal('total_amount', 15, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->nullable();
            $table->longText('shipping_address')->nullable();
            $table->string('billing_address')->nullable();
            $table->datetime('placed_at');
            $table->string('order_status');
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
